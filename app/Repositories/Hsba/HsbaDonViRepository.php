<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HsbaDonVi;
use Carbon\Carbon;

class HsbaDonViRepository extends BaseRepositoryV2
{
    const TAT_CA_TRANG_THAI = -1;
    const BENH_AN_KHAM_BENH = 24;
    const BENH_AN_KHONG_KHAM_BENH = 0;
    const TRANG_THAI_CHO_KHAM = 1;
    const TRANG_THAI_DANG_KHAM = 2;
    const TRANG_THAI_DA_THANH_TOAN = 1;
    const CHO_DIEU_TRI = 0;
    const DANG_DIEU_TRI = 2;
    const DONG_HSBA = 1;
    
    // Params KhoaPhong
    private $benhVienId = null;
    private $khoaId = null;
    private $phongId = null;
    private $loaiBenhAn = null;
    // Params Pagination
    private $limit = 20;
    private $page = 1;
    private $query = null;
    // Others
    private $keyword = '';
    private $statusHsbaDv = null;
    private $statusHsba = null;
    private $khoangThoiGianVaoVien = [];
    private $khoangThoiGianRaVien = [];
    
    public function setKhoaPhongParams(int $benhVienId, int $khoaId, $phongId) {
        $this->benhVienId = $benhVienId;
        $this->khoaId = $khoaId;
        $this->phongId = $phongId;
        return $this;
    }
    
    public function setPaginationParams($limit, $page) {
        // limit, page
        $this->limit = $limit;
        $this->page = $page;
        return $this;
    }
    
    public function setKeyWordParams($keyword) {
        $this->keyword = $keyword;
        return $this;
    }
    
    public function setLoaiBenhAnParams($loaiBenhAn) {
        $this->loaiBenhAn = $loaiBenhAn;
        return $this;
    }
    
    public function setStatusHsbaDvParams($statusHsbaDv) {
        $this->statusHsbaDv = $statusHsbaDv;
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
    
    public function getModel()
    {
        return HsbaDonVi::class;
    }
    
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($hsbaDonViId,array $params)
    {
        $hsbaDonVi = $this->model->findOrFail($hsbaDonViId);
		$hsbaDonVi->update($params);
    }    
    
    public function getById($hsbaDonViId)
    {
        $where = [
            ['hsba_don_vi.id', '=', $hsbaDonViId],
        ];
        
        $result = $this->model->where($where)->get()->first();
        
        return $result;
    }
    
    public function getListV2()
    {
        if (
            ($this->khoaId === null && $this->phongId === null) || $this->benhVienId === null
        ) {
            throw new \Exception("In valid data");
        }
        
        $page = $this->page;
        $limit = $this->limit;
        $offset = ($page - 1) * $limit;
        $loaiBenhAn = self::BENH_AN_KHAM_BENH;
        
        $where = [
            ['hsba_don_vi.benh_vien_id', '=', $this->benhVienId],
            ['hsba_don_vi.loai_benh_an', '<>', self::BENH_AN_KHONG_KHAM_BENH]
        ];
        
        if ($this->phongId === null) {
            $where[] = ['hsba_don_vi.khoa_hien_tai', '=', $this->khoaId];
        } else {
            $where[] = ['hsba_don_vi.phong_hien_tai', '=', $this->phongId];
        }

        $column = [
            'hsba.id as hsba_id',
            'hsba_don_vi.id as hsba_don_vi_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao',
            'hsba.ngay_ra_vien',
            'hsba_don_vi.thoi_gian_vao_vien',
            'hsba_don_vi.thoi_gian_ra_vien',
            'hsba_don_vi.trang_thai_cls',
            'tt1.diengiai as ten_trang_thai_cls',
            'hsba_don_vi.trang_thai',
            'tt2.diengiai as ten_trang_thai',
            'vien_phi.trang_thai as vien_phi_trang_thai',
            'vien_phi.loai_vien_phi',
            'hsba_don_vi.trang_thai_thanh_toan'
        ];
        
        // echo "<pre>";
        // print_r($where);
        // echo "</pre>";
        // die();
        
        // $query = $this->model->where($where);
        // $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
        //                 ->offset($offset)
        //                 ->limit($limit)
        //                 ->get();
                        
        // $data->each(function ($item, $key) {
        //     $item->hsba_id = sprintf('%012d', $item->hsba_id);
        //     $item->so_thu_tu = sprintf('%03d', $item->so_thu_tu);
        // });
        
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        
        $query = $this->model
            ->leftJoin('hsba', 'hsba.id', '=', 'hsba_don_vi.hsba_id')
            ->leftJoin('vien_phi', 'vien_phi.id', '=', 'hsba_don_vi.vien_phi_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_don_vi.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_don_vi.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            });
            
        if($this->phongId > 0) {
            $query = $query->leftJoin('stt_phong_kham as sttpk', function($join) {
                $join->on('sttpk.hsba_id', '=', 'hsba_don_vi.hsba_id')
                    ->where('sttpk.phong_id', '=', $this->phongId);
            });
            $query = $query->whereIn('sttpk.trang_thai', [self::TRANG_THAI_CHO_KHAM, self::TRANG_THAI_DANG_KHAM])
                    ->where('hsba_don_vi.trang_thai_thanh_toan', '=', self::TRANG_THAI_DA_THANH_TOAN);
            
            $arrayColumn = [
                'sttpk.loai_stt',
                'sttpk.so_thu_tu',
                'sttpk.stt_don_tiep_id',
            ];
            
            $column = array_merge($column, $arrayColumn);
        }
            
        $query = $query->where($where);
        
        if ($this->khoangThoiGianVaoVien || $this->khoangThoiGianRaVien) {
            if ($this->khoangThoiGianVaoVien['from'] && $this->khoangThoiGianVaoVien['to']) {
                $filterColumn = 'thoi_gian_vao_vien';
                $from = $this->khoangThoiGianVaoVien['from'];
                $to = $this->khoangThoiGianVaoVien['to'];
            } elseif ($this->khoangThoiGianRaVien['from'] && $this->khoangThoiGianRaVien['to']) {
                $filterColumn = 'thoi_gian_ra_vien';
                $from = $this->khoangThoiGianRaVien['from'];
                $to = $this->khoangThoiGianRaVien['to'];
            }
            
            if($from == $to){
                $query = $query->whereDate($filterColumn, '=', $from);
            } else {
                $query = $query->whereBetween($filterColumn, [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            }
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
        
        if($this->statusHsbaDv != self::TAT_CA_TRANG_THAI && $this->phongId) {
            $query = $query->where(function($queryAdv) {
                if($this->statusHsbaDv == 0){
                    $queryAdv->whereIn('hsba_don_vi.trang_thai', [self::CHO_DIEU_TRI,self::DANG_DIEU_TRI])
                            ->where('hsba_don_vi.trang_thai_thanh_toan', '=', self::TRANG_THAI_DA_THANH_TOAN);
                }
                else {
                    $queryAdv->where('hsba_don_vi.trang_thai', '=', $this->statusHsbaDv);
                }
            });
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
            /*
            
            */
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
            $data->each(function ($item, $key) {
                $item->hsba_id = sprintf('%012d', $item->hsba_id);
                $item->so_thu_tu = sprintf('%03d', $item->so_thu_tu);
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
    
    public function getListPhongNoiTru() {
        $page = $this->page;
        $limit = $this->limit;
        $offset = ($page - 1) * $limit;
        
        $where = [
            ['hsba_don_vi.benh_vien_id', '=', $this->benhVienId],
            ['hsba_don_vi.khoa_hien_tai', '=', $this->khoaId],
            ['hsba_don_vi.phong_hien_tai', '=', $this->phongId],
            ['hsba.trang_thai_hsba', '<>', self::DONG_HSBA],
            ['hsba_don_vi.khoa_chuyen_den', '=', null],
            ['hsba_don_vi.phong_chuyen_den', '=', null]
        ];

        $column=[
            'hsba.id as hsba_id',
            'hsba_don_vi.id as hsba_don_vi_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao',
            'hsba.ngay_ra_vien',
            'hsba_don_vi.thoi_gian_vao_vien',
            'hsba_don_vi.thoi_gian_ra_vien',
        ];
        
        $query = $this->model
            ->leftJoin('hsba', 'hsba.id', '=', 'hsba_don_vi.hsba_id')
            ->leftJoin('vien_phi', 'vien_phi.id', '=', 'hsba_don_vi.vien_phi_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_don_vi.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_don_vi.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            });
        $query = $query->where($where);
        
        if ($this->khoangThoiGianVaoVien) {
            if ($this->khoangThoiGianVaoVien['from'] && $this->khoangThoiGianVaoVien['to']) {
                $filterColumn = 'thoi_gian_vao_vien';
                $from = $this->khoangThoiGianVaoVien['from'];
                $to = $this->khoangThoiGianVaoVien['to'];
            }
            
            if($from == $to){
                $query = $query->whereDate($filterColumn, '=', $from);
            } else {
                $query = $query->whereBetween($filterColumn, [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            }
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
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            /*
            
            */
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
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
    
    public function getListPhongHanhChinh() {
        $page = $this->page;
        $limit = $this->limit;
        $offset = ($page - 1) * $limit;
        $loaiBenhAn = self::BENH_AN_KHAM_BENH;
        
        $where = [
            ['hsba_don_vi.benh_vien_id', '=', $this->benhVienId],
            ['hsba_don_vi.khoa_chuyen_den', '=', $this->khoaId],
            ['hsba_don_vi.phong_chuyen_den', '=', $this->phongId],
            ['hsba_don_vi.loai_benh_an', '<>', self::BENH_AN_KHONG_KHAM_BENH]
        ];
        
        $column=[
            'hsba.id as hsba_id',
            'hsba_don_vi.id as hsba_don_vi_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao',
            'hsba.ngay_ra_vien',
            'hsba_don_vi.thoi_gian_vao_vien',
            'hsba_don_vi.thoi_gian_ra_vien',
        ];
        
        $query = $this->model
            ->leftJoin('hsba', 'hsba.id', '=', 'hsba_don_vi.hsba_id')
            ->leftJoin('vien_phi', 'vien_phi.id', '=', 'hsba_don_vi.vien_phi_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_don_vi.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_don_vi.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            });
        $query = $query->where($where);
        
        if ($this->khoangThoiGianRaVien) {
            if ($this->khoangThoiGianRaVien['from'] && $this->khoangThoiGianRaVien['to']) {
                $filterColumn = 'thoi_gian_ra_vien';
                $from = $this->khoangThoiGianRaVien['from'];
                $to = $this->khoangThoiGianRaVien['to'];
            }
            
            if($from == $to){
                $query = $query->whereDate($filterColumn, '=', $from);
            } else {
                $query = $query->whereBetween($filterColumn, [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            }
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
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            /*
            
            */
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
            $data->each(function ($item, $key) {
                $item->hsba_id = sprintf('%012d', $item->hsba_id);
                $item->so_thu_tu = sprintf('%03d', $item->so_thu_tu);
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
    
    public function getPhongChoByHsbaId($hsbaId, $phongId)
    {
        $where = [
            ['hsba_don_vi.loai_benh_an', '<>', self::BENH_AN_KHONG_KHAM_BENH],
            ['hsba.id', '=', $hsbaId],
            ['hsba_don_vi.phong_chuyen_den', '=', $phongId]
        ];
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'tt2.diengiai as doi_tuong_benh_nhan',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            //'vienphi.vienphicode',
            'khoa.ten_khoa',
            //'khoa.kho_thuoc',
            //'khoa.kho_vat_tu',
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
            'hsba.trang_thai_hsba',
            'hsba.nguoi_than',
            'hsba.ms_bhyt',
            'hsba.ma_cskcbbd',
            //'bhyt.id as bhyt_id',
            //'bhyt.ma_cskcbbd',
            //'bhyt.tu_ngay',
            //'bhyt.den_ngay',
            //'bhyt.ma_noi_song',
            //'bhyt.du5nam6thangluongcoban',
            //'bhyt.dtcbh_luyke6thang',
            'hsba_don_vi.doi_tuong_benh_nhan as doi_tuong_benh_nhan_id',
            'hsba_don_vi.trang_thai',
            'hsba_don_vi.khoa_hien_tai',
            'hsba_don_vi.id as hsba_don_vi_id',
            'hsba_don_vi.cdvv_icd10_text',
            'hsba_don_vi.cdvv_icd10_code',
            //'hsba_don_vi.ly_do_vao_vien',
            //'hsba_don_vi.qua_trinh_benh_ly',
            //'hsba_don_vi.tien_su_benh_ban_than',
            //'hsba_don_vi.tien_su_benh_gia_dinh',
            'hsba_don_vi.cdtd_icd10_text',
            'hsba_don_vi.cdtd_icd10_code',
            'hsba_don_vi.noi_gioi_thieu_id',
            'hsba_don_vi.phong_hien_tai',
            'hsba_don_vi.thoi_gian_vao_vien',
            'hsba_don_vi.hinh_thuc_vao_vien_id',
            'hsba_don_vi.thoi_gian_ra_vien',
            'hsba_don_vi.cdrv_icd10_code',
            'hsba_don_vi.cdrv_icd10_text',
            //'hsba_don_vi.cdrv_kt_icd10_code',
            //'hsba_don_vi.cdrv_kt_icd10_text',
            'hsba_don_vi.ket_qua_dieu_tri',
            'hsba_don_vi.hinh_thuc_ra_vien',
            // 'hsba_don_vi.kham_toan_than',
            // 'hsba_don_vi.kham_bo_phan',
            // 'hsba_don_vi.ket_qua_can_lam_san',
            // 'hsba_don_vi.huong_xu_ly',
            // 'hsba_don_vi.tom_tat_benh_an',
            // 'hsba_don_vi.tien_luong',
            // 'hsba_don_vi.mach',
            // 'hsba_don_vi.nhiet_do',
            // 'hsba_don_vi.nhip_tho',
            // 'hsba_don_vi.sp_o2',
            // 'hsba_don_vi.can_nang',
            // 'hsba_don_vi.chieu_cao',
            // 'hsba_don_vi.thi_luc_mat_trai',
            // 'hsba_don_vi.thi_luc_mat_phai',
            // 'hsba_don_vi.kl_thi_luc_mat_trai',
            // 'hsba_don_vi.kl_thi_luc_mat_phai',
            // 'hsba_don_vi.nhan_ap_mat_trai',
            // 'hsba_don_vi.nhan_ap_mat_phai',
            // 'hsba_don_vi.huyet_ap_thap',
            // 'hsba_don_vi.huyet_ap_cao',
            // 'hsba_don_vi.chan_doan_ban_dau',
            // 'hsba_don_vi.upload_file_hoi_benh',
            // 'hsba_don_vi.upload_file_kham_benh',
            //'vien_phi.loai_vien_phi',
            //'vien_phi.id as vien_phi_id',
            //'bhyt.tuyen_bhyt',
            //'dieu_tri.id as phieu_dieu_tri_id'
        ];
        
        $query = $this->model
                ->rightJoin('hsba', 'hsba.id', '=', 'hsba_don_vi.hsba_id')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'hsba_don_vi.loai_benh_an')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'hsba_don_vi.doi_tuong_benh_nhan')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->leftJoin('khoa', 'khoa.id', '=', 'hsba_don_vi.khoa_hien_tai')
                ->leftJoin('phong', 'phong.id', '=', 'hsba_don_vi.phong_hien_tai');
                //->leftJoin('bhyt', 'bhyt.id', '=', 'hsba_don_vi.bhyt_id')
                //->leftJoin('vien_phi', 'vien_phi.hsba_id', '=', 'hsba.id');
            
        $data = $query->where($where)->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
    
    public function getByHsbaId($hsbaId, $phongId, $dataBenhVienThietLap)
    {
        $where = [
            ['hsba_don_vi.loai_benh_an', '<>', self::BENH_AN_KHONG_KHAM_BENH],
            ['hsba.id', '=', $hsbaId]
        ];
        
        $khoaHienTai = $dataBenhVienThietLap['khoaHienTai']; //khoa kham benh
        $phongDonTiepID = $dataBenhVienThietLap['phongDonTiepID'];
        
        if ($phongDonTiepID == $phongId) {
            $where[] = ['hsba_don_vi.khoa_hien_tai', '=', $khoaHienTai];
        } else {
            $where[] = ['hsba_don_vi.phong_hien_tai', '=', $phongId];
        }
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            //'vienphi.vienphicode',
            'khoa.ten_khoa',
            //'khoa.kho_thuoc',
            //'khoa.kho_vat_tu',
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
            'hsba.trang_thai_hsba',
            'hsba.nguoi_than',
            'hsba.ms_bhyt',
            'hsba.ma_cskcbbd',
            'bhyt.id as bhyt_id',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
            'tt2.diengiai as doi_tuong_benh_nhan',
            'hsba_don_vi.doi_tuong_benh_nhan as doi_tuong_benh_nhan_id',
            'hsba_don_vi.trang_thai',
            'hsba_don_vi.khoa_hien_tai',
            'hsba_don_vi.phong_hien_tai',
            'hsba_don_vi.id as hsba_don_vi_id',
            'hsba_don_vi.cdvv_icd10_text',
            'hsba_don_vi.cdvv_icd10_code',
            'hsba_don_vi.loai_benh_an as loai_benh_an_id',
            'hsba.cdrv_benh_chinh_code',
            'hsba.cdrv_benh_chinh_text',
            'hsba.cdrv_benh_phu_code',
            'hsba.cdrv_benh_phu_text',
            'vien_phi.loai_vien_phi',
            'vien_phi.id as vien_phi_id',
            'bhyt.tuyen_bhyt',
            'sttpk.loai_stt',
            'sttpk.stt_don_tiep_id',
            'dieu_tri.id as phieu_dieu_tri_id',
            'phong_benh.ten as phong_benh',
            'giuong_benh.stt as giuong_benh'
        ];
        
        $query = $this->model
                ->rightJoin('hsba', 'hsba.id', '=', 'hsba_don_vi.hsba_id')
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
                ->leftJoin('vien_phi', 'vien_phi.hsba_id', '=', 'hsba.id')
                ->leftJoin('stt_phong_kham as sttpk', function($join) use ($hsbaId) {
                    $join->on('sttpk.hsba_id', '=', 'hsba_don_vi.hsba_id')
                        ->where('sttpk.hsba_id', '=', $hsbaId)
                        ->orderBy('sttpk.id', 'desc');
                })
                ->leftJoin('dieu_tri', function($join) use ($hsbaId) {
                    $join->on('dieu_tri.hsba_don_vi_id', '=', 'hsba_don_vi.id')
                        ->on('dieu_tri.khoa_id', '=', 'hsba_don_vi.khoa_hien_tai')
                        ->on('dieu_tri.phong_id', '=', 'hsba_don_vi.phong_hien_tai')
                        ->where('dieu_tri.hsba_id', '=', $hsbaId);
                })
                ->leftJoin('phong_benh', 'phong_benh.id', '=', 'hsba_don_vi.buong_hien_tai')
                ->leftJoin('giuong_benh', 'giuong_benh.id', '=', 'hsba_don_vi.giuong_hien_tai');
            
        $data = $query->where($where)->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
    
    public function batDauKham($hsbaDonViId)
    {
		$this->model->where('id', '=', $hsbaDonViId)->update(['thoi_gian_vao_vien' => Carbon::now()->toDateTimeString()]);
    }
    
    public function getLichSuKhamDieuTri($id)
    {
        $where = [
            ['hsba_don_vi.benh_nhan_id', '=', $id],
        ];
        $column=[
            'phong.ten_phong',
            'hsba_don_vi.thoi_gian_vao_vien',
            'hsba_don_vi.thoi_gian_ra_vien',
            'hsba_don_vi.cdrv_icd10_text'
        ];
        $result = $this->model->leftJoin('phong','phong.id','=','hsba_don_vi.phong_hien_tai')
                            ->where($where)
                            ->get($column);
        return $result;
    } 
}