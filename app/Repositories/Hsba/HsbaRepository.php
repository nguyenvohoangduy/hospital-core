<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Hsba;
use App\Http\Resources\HsbaResource;
use App\Helper\Util;
use Carbon\Carbon;

class HsbaRepository extends BaseRepositoryV2
{
    const TAT_CA_BENH_AN = -1;
    const TAT_CA_TRANG_THAI = -1;
    const VIEN_PHI = 'VP';
    const BAO_HIEM = 'BH';
    const LOAI_VIEN_PHI_BINH_THUONG = 1;
    const LOAI_VIEN_PHI_BAO_HIEM = 2;
    
    // Params Pagination
    private $limit = 20;
    private $page = 1;
    private $query = null;
    // Others
    private $keyword = '';
    private $benhVienId = null;
    private $loaiBenhAn = null;
    private $loaiVienPhi = null;
    private $statusHsba = null;
    private $khoangThoiGianVaoVien = [];
    private $khoangThoiGianRaVien = [];
    
    public function getModel()
    {
        return Hsba::class;
    }
    
    public function setBenhVienParams(int $benhVienId) {
        $this->benhVienId = $benhVienId;
        return $this;
    }
    
    public function setPaginationParams($limit, $page) {
        // limit, page
        $this->limit = $limit;
        $this->page = $page;
        return $this;
    }
    
    public function setLoaiVienPhiParams($loaiVienPhi) {
        $this->loaiVienPhi = $loaiVienPhi;
        return $this;
    }
    
    public function setLoaiBenhAnParams($loaiBenhAn) {
        $this->loaiBenhAn = $loaiBenhAn;
        return $this;
    }
    
    public function setKeyWordParams($keyword) {
        $this->keyword = $keyword;
        return $this;
    }
    
    public function setStatusHsbaParams(int $statusHsba) {
        $this->statusHsba = $statusHsba;
        return $this;
    }
    
    public function setKhoangThoiGianVaoVienParams($from, $to) {
        // todo validate
        $this->khoangThoiGianVaoVien = ['from' => $from, 'to' => $to];
        return $this;
    }
    
    public function setKhoangThoiGianRaVienParams($from, $to) {
        // todo validate
        $this->khoangThoiGianRaVien = ['from' => $from, 'to' => $to];
        return $this;
    }
    
    public function getList()
    {
        if ($this->benhVienId === null) {
            throw new \Exception("In valid data");
        }
        
        $page = $this->page;
        $limit = $this->limit;
        $offset = ($page - 1) * $limit;
        
        $where = [
            ['hsba.benh_vien_id', '=', $this->benhVienId]
        ];
        
        if($this->loaiBenhAn != self::TAT_CA_BENH_AN) {
            $where[] = ['hsba.loai_benh_an', '=', $this->loaiBenhAn];
        }
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao as thoi_gian_vao_vien',
            'hsba.ngay_ra_vien as thoi_gian_ra_vien',
            'hsba.loai_benh_an',
            'hsba.khoa_id',
            'hsba.phong_id'
        ];
        
        $query = $this->model->where($where);
        
        if ($this->khoangThoiGianVaoVien || $this->khoangThoiGianRaVien) {
            if ($this->khoangThoiGianVaoVien['from'] && $this->khoangThoiGianVaoVien['to']) {
                $filterColumn = 'ngay_tao';
                $from = $this->khoangThoiGianVaoVien['from'];
                $to = $this->khoangThoiGianVaoVien['to'];
            } elseif ($this->khoangThoiGianRaVien['from'] && $this->khoangThoiGianRaVien['to']) {
                $filterColumn = 'ngay_ra_vien';
                $from = $this->khoangThoiGianRaVien['from'];
                $to = $this->khoangThoiGianRaVien['to'];
            }
            
            if($from == $to){
                $query = $query->whereDate($filterColumn, '=', $from);
            } else {
                $query = $query->whereBetween($filterColumn, [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            }
        }
        
        if($this->loaiVienPhi) {
            $query = $query->leftJoin('vien_phi', function($join) {
                if($this->loaiVienPhi === self::VIEN_PHI) {
                    $join->on('vien_phi.hsba_id', '=', 'hsba.id')
                        ->where('vien_phi.loai_vien_phi', '=', self::LOAI_VIEN_PHI_BINH_THUONG);
                }
                else if($this->loaiVienPhi === self::BAO_HIEM) {
                    $join->on('vien_phi.hsba_id', '=', 'hsba.id')
                        ->where('vien_phi.loai_vien_phi', '=', self::LOAI_VIEN_PHI_BAO_HIEM);
                } 
                else {
                    $join->on('vien_phi.hsba_id', '=', 'hsba.id')
                        ->whereIn('vien_phi.loai_vien_phi', [self::LOAI_VIEN_PHI_BINH_THUONG, self::LOAI_VIEN_PHI_BAO_HIEM]);
                }
            });
        }
        
        if($this->keyword != '') {
            $query = $query->where(function($queryAdv) {
                $keyword = $this->keyword;
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('hsba.ten_benh_nhan', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$keyword.'%')
                        ->orWhereRaw("cast(hsba.id as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$upperCase%'");
            });
        }
        
        if($this->statusHsba != self::TAT_CA_TRANG_THAI) {
            $query = $query->where('hsba.trang_thai_hsba', '=', $this->statusHsba);
        }
        
        // TO DO : Store SQL Log
        /*
        $sql = str_replace(array('?'), array('\'%s\''), $query->toSql());
        $sql = vsprintf($sql, $query->getBindings());
        var_dump($sql);die;
        */
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('ngay_tao', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
            $data->each(function ($item, $key) {
                $item->hsba_id = sprintf('%012d', $item->hsba_id);
            });
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
        
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }
    
    public function getHsbaByBenhNhanId($benhNhanId)
    {
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'hsba.ten_benh_nhan as ho_va_ten',
            'hsba.ten_benh_nhan',
            'hsba.gioi_tinh_id as gioi_tinh',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.nghe_nghiep_id',
            'hsba.dan_toc_id',
            'hsba.quoc_tich_id',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.phuong_xa_id',
            'hsba.quan_huyen_id',
            'hsba.tinh_thanh_pho_id',
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            // 'hsba.loai_nguoi_than',
            // 'hsba.ten_nguoi_than',
            // 'hsba.dien_thoai_nguoi_than',
            'hsba.nguoi_than',
            'hsba.loai_benh_an',
            'hsba.trang_thai_hsba',
            'hsba.is_dang_ky_truoc',
            'hsba.phong_id',
            'phong.ten_phong',
            'hsba.ms_bhyt',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
            'benh_nhan.ma_so_thue',
            'benh_nhan.so_cmnd',
            'benh_nhan.ma_tiem_chung',
            'benh_nhan.url_hinh_anh',
            'bhyt.tuyen_bhyt',
            'bhyt.ma_cskcbbd',
        ];
        
        $result = $this->model->where('hsba.benh_nhan_id', $benhNhanId)
                            ->leftJoin('phong', 'phong.id', '=', 'hsba.phong_id')
                            ->leftJoin('bhyt', 'bhyt.ms_bhyt', '=', 'hsba.ms_bhyt')
                            ->leftJoin('benh_nhan', 'benh_nhan.id', '=', 'hsba.benh_nhan_id')
                            ->get($column)
                            ->first();
        
        return $result;
    }
    
    public function getHsbaByHsbaId($hsbaId)
    {
        $where = [
            ['hsba_don_vi.loai_benh_an', '=', self::BENH_AN_KHAM_BENH],
            ['hsba.id', '=', $hsbaId]
        ];
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            //'vienphi.vienphicode',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'hsba.ten_benh_nhan',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.gioi_tinh_id as gioi_tinh',
            'hsba.nghe_nghiep_id',
            'hsba.dan_toc_id',
            'hsba.quoc_tich_id',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.phuong_xa_id',
            'hsba.quan_huyen_id',
            'hsba.tinh_thanh_pho_id',
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.ms_bhyt',
            'hsba.thx_gplace_json',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
            'tt2.diengiai as doi_tuong_benh_nhan',
            // 'hsba_khoa_phong.trang_thai',
            // 'hsba_khoa_phong.khoa_hien_tai',
            // 'hsba_khoa_phong.id as hsba_khoa_phong_id',
            // 'hsba_khoa_phong.cdvv_icd10_text',
            // 'hsba_khoa_phong.cdvv_icd10_code',
            // 'hsba_khoa_phong.ly_do_vao_vien',
            // 'hsba_khoa_phong.qua_trinh_benh_ly',
            // 'hsba_khoa_phong.tien_su_benh_ban_than',
            // 'hsba_khoa_phong.tien_su_benh_gia_dinh',
            // 'hsba_khoa_phong.cdtd_icd10_text',
            // 'hsba_khoa_phong.cdtd_icd10_code',
            // 'hsba_khoa_phong.noi_gioi_thieu_id',
            // 'hsba_khoa_phong.phong_hien_tai',
            // 'hsba_khoa_phong.thoi_gian_vao_vien',
            // 'hsba_khoa_phong.hinh_thuc_vao_vien_id',
            // 'hsba_khoa_phong.thoi_gian_ra_vien',
            // 'hsba_khoa_phong.cdrv_icd10_code',
            // 'hsba_khoa_phong.cdrv_icd10_text',
            // 'hsba_khoa_phong.cdrv_kt_icd10_code',
            // 'hsba_khoa_phong.cdrv_kt_icd10_text',
            // 'hsba_khoa_phong.ket_qua_dieu_tri',
            // 'hsba_khoa_phong.hinh_thuc_ra_vien',
            // 'hsba_khoa_phong.kham_toan_than',
            // 'hsba_khoa_phong.kham_bo_phan',
            // 'hsba_khoa_phong.ket_qua_can_lam_san',
            // 'hsba_khoa_phong.huong_xu_ly',
            // 'hsba_khoa_phong.tom_tat_benh_an',
            // 'hsba_khoa_phong.tien_luong',
            // 'hsba_khoa_phong.mach',
            // 'hsba_khoa_phong.nhiet_do',
            // 'hsba_khoa_phong.nhip_tho',
            // 'hsba_khoa_phong.sp_o2',
            // 'hsba_khoa_phong.can_nang',
            // 'hsba_khoa_phong.chieu_cao',
            // 'hsba_khoa_phong.thi_luc_mat_trai',
            // 'hsba_khoa_phong.thi_luc_mat_phai',
            // 'hsba_khoa_phong.kl_thi_luc_mat_trai',
            // 'hsba_khoa_phong.kl_thi_luc_mat_phai',
            // 'hsba_khoa_phong.nhan_ap_mat_trai',
            // 'hsba_khoa_phong.nhan_ap_mat_phai',
            // 'hsba_khoa_phong.huyet_ap_thap',
            // 'hsba_khoa_phong.huyet_ap_cao',
            // 'hsba_khoa_phong.chan_doan_ban_dau',
            'vien_phi.loai_vien_phi',
            'vien_phi.id as vien_phi_id',
            'bhyt.tuyen_bhyt',
            // 'stt_phong_kham.loai_stt',
            // 'stt_phong_kham.stt_don_tiep_id',
        ];
        
        $query = $this->model
                ->leftJoin('hsba_don_vi', 'hsba_don_vi.hsba_id', '=', 'hsba.id')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'hsba_don_vi.loai_benh_an')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'hsba_don_vi.doi_tuong_benh_nhan')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->leftJoin('khoa', 'khoa.id', '=', 'hsba_don_vi.khoa_hien_tai')
                ->leftJoin('phong', 'phong.id', '=', 'hsba_don_vi.phong_hien_tai')
                ->leftJoin('bhyt', 'bhyt.id', '=', 'hsba_don_vi.bhyt_id')
                ->leftJoin('vien_phi', 'vien_phi.hsba_id', '=', 'hsba.id');
                // ->leftJoin('stt_phong_kham', function($join) use ($hsbaId) {
                //     $join->on('stt_phong_kham.hsba_id', '=', $hsbaId)
                //         ->orderBy('stt_phong_kham.id', 'desc');
                // });
            
        $data = $query->where($where)->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
  
    public function createDataHsba(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateHsba($hsbaId, $input)
    {
        $hsba = $this->model->findOrFail($hsbaId);
		$hsba->update($input);
    }
    
    public function getById($hsbaId)
    {
        $result = $this->model->findOrFail($hsbaId);
        return $result;
    }
    
    public function listBenhNhanTrung($ho_va_ten, $ngay_sinh, $gioi_tinh_id)
    {
        $result = DB::table("benh_nhan")
                            ->leftJoin(DB::raw('(select benh_nhan_id, MAX(tu_ngay) tu_ngay
                                from bhyt GROUP BY benh_nhan_id) as bh'), function ($join) {
                                    $join->on ( 'bh.benh_nhan_id', '=', 'benh_nhan.id' ); })
                            ->leftJoin("bhyt", function($join){
                                    $join->On("bhyt.benh_nhan_id", "=", "bh.benh_nhan_id");
                                    $join->On("bhyt.tu_ngay", "=", "bh.tu_ngay");
                            })
                            ->leftJoin(DB::raw('hanh_chinh as tinh'), function ($join) {
                                    $join->on (DB::raw("cast(tinh.ma_tinh as text)"), '=', 'benh_nhan.tinh_thanh_pho_id' ); })
                            ->leftJoin(DB::raw('hanh_chinh as huyen'), function ($join) {
                                    $join->on (DB::raw("cast(huyen.ma_huyen as text)"), '=', 'benh_nhan.quan_huyen_id' );
                                    $join->on (DB::raw("cast(huyen.huyen_matinh as text)"), '=', 'benh_nhan.tinh_thanh_pho_id' );})
                            ->leftJoin(DB::raw('hanh_chinh as xa'), function ($join) {
                                    $join->on (DB::raw("cast(xa.ma_xa as text)"), '=', 'benh_nhan.phuong_xa_id' );
                                    $join->on (DB::raw("cast(xa.xa_mahuyen as text)"), '=', 'benh_nhan.quan_huyen_id' );
                                    $join->on (DB::raw("cast(xa.xa_matinh as text)"), '=', 'benh_nhan.tinh_thanh_pho_id' );})
                            ->whereRaw('LOWER(trim(benh_nhan.ho_va_ten)) = ?', mb_strtolower(trim($ho_va_ten)))
                            ->where('benh_nhan.ngay_sinh', '=', $ngay_sinh)
                            ->where('benh_nhan.gioi_tinh_id', '=', $gioi_tinh_id)
                            ->get(['benh_nhan.id', 'benh_nhan.ho_va_ten as ten', 'bhyt.ms_bhyt'
                                    , DB::raw("CONCAT(benh_nhan.so_nha, ' ', benh_nhan.duong_thon, ', ', xa.ten_xa, ', ', huyen.ten_huyen, ', ', tinh.ten_tinh) as dia_chi")
                                    , 'benh_nhan.gioi_tinh_id as gioi_tinh', 'benh_nhan.ngay_sinh', 'benh_nhan.so_cmnd']);
        return $result;
    }
}