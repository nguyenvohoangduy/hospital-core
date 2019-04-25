<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;

// Repositories
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\Hsba\HsbaDonViRepository;
use App\Repositories\Hsba\HsbaKhoaPhongRepository; 
use App\Repositories\Bhyt\BhytRepository; 
use App\Repositories\VienPhi\VienPhiRepository; 
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhongRepository;
use App\Repositories\HanhChinhRepository;
use App\Repositories\ChuyenVienRepository;
use App\Repositories\BenhVienRepository;
use App\Repositories\DanhMuc\NoiGioiThieuRepository;

// Service
use App\Services\SttPhongKhamService;
use App\Services\HsbaKhoaPhongService;
use App\Services\VienPhiService;

// Others
use App\Helper\Util;
use Carbon\Carbon;

//Value objects
use App\Models\ValueObjects\NhomNguoiThan;

use Validator;
use App\Helper\AwsS3;
use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use App\Repositories\Sqs\Dkkb\InputLogRepository as InputLogSqsRepository;
use App\Log\DangKyKhamBenhErrorLog;
use Aws\S3\S3Client;

class BenhNhanServiceV2 {
    
    private $dataBenhNhan = [];
    private $dataHsba = [];
    private $dataHsbaDv = [];    
    private $dataHsbaKp = [];
    private $dataYeuCauKham = [];
    private $dataSttPk = [];
    private $dataVienPhi = [];
    private $dataDieuTri = [];
    private $dataPhieuYLenh = [];
    private $dataYLenh = [];
    private $dataNgheNghiep = [];
    private $dataDanToc = [];
    private $dataQuocTich = [];
    private $dataTinh = [];
    private $dataHuyen = [];
    private $dataXa = [];
    //private $dataTHX = null;
    private $dataTenTHX = [];
    private $dataNhomNguoiThan = null;
    private $dataChuyenVien = [];
    
    private $dataLog = [];
    const LOAI_NOI_GIOI_THIEU_KHONG_HUE_HONG = 0;
    const LOAI_NOI_GIOI_THIEU_CO_HUE_HONG = 1;
    const LOAI_GIOI_THIEU_TRONG_DANH_SACH = "0";
    const LOAI_GIOI_THIEU_TU_DEN = "1";
    const LOAI_GIOI_THIEU_KHAC = "2";
    
    const TYPE_LOG_INPUT = 'input';
    const TYPE_LOG_ERROR = 'error';
    
    const VI_DIEN_TU_MAC_DINH = 0;
    
    private $benhNhanKeys = [
        'benh_nhan_id', 'ho_va_ten', 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id', 'ma_so_thue', 'so_cmnd', 'ma_tiem_chung', 'hinh_benh_nhan', 'benh_vien_id'
    ];
    
    private $hsbaKeys = [
        'auth_users_id', 'khoa_id'
        , 'ngay_sinh', 'gioi_tinh_id'
        , 'so_nha', 'duong_thon', 'noi_lam_viec'
        , 'url_hinh_anh', 'dien_thoai_benh_nhan', 'email_benh_nhan', 'dia_chi_lien_he'
        , 'ms_bhyt', 'benh_vien_id'
        , 'tinh_thanh_pho_id' , 'quan_huyen_id' , 'phuong_xa_id', 'noi_gioi_thieu_id', 'noi_gioi_thieu_khac', 'loai_gioi_thieu', 'ma_cskcbbd'
    ];
    
    private $hsbaKpKeys = [
        'auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'cdtd_icd10_text', 'cdtd_icd10_code'
        ,'benh_vien_id', 'loai_vien_phi'
    ];
    
    private $bhytKeys = ['benh_nhan_id', 'ms_bhyt', 'ma_cskcbbd', 'tu_ngay', 'den_ngay', 'ma_noi_song', 'du5nam6thangluongcoban', 'dtcbh_luyke6thang', 'tuyen_bhyt'];
    
    private $sttPkKeys = [
        'loai_stt', 'ma_nhom', 'stt_don_tiep_id'
    ];
    
    private $dieuTriKeys = [
        'cd_icd10_code', 'cd_icd10_text'
    ];
    
    private $chuyenVienKeys = [
        'khoa_id', 'chuyen_tuyen_hoi','benh_vien_tuyen_duoi_code',
        'tinh_trang_chuyen_tuyen','ly_do_chuyen_tuyen','phuong_tien_van_chuyen',
        'ten_nguoi_ho_tong','dau_hieu_lam_sang','phuong_phap_thu_thuat','ket_qua_xet_nghiem',
        'huong_dieu_tri','chan_doan','chan_doan_tuyen_duoi_code','chan_doan_tuyen_duoi'
    ];
    
    private $hsbaDvKeys = [
        'auth_users_id', 'doi_tuong_benh_nhan', 'yeu_cau_kham_id', 'cdtd_icd10_code', 'cdtd_icd10_text','noi_gioi_thieu_id','benh_vien_chuyen_toi'
        ,'benh_vien_id','loai_vien_phi'
    ];    
    
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService,
        SttPhongKhamService $sttPhongKhamService,
        VienPhiService $vienPhiService,

        BenhNhanRepository $benhNhanRepository, 
        HsbaRepository $hsbaRepository, 
        HsbaDonViRepository $hsbaDonViRepository,
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository, 
        DanhMucTongHopRepository $danhMucTongHopRepository, 
        BhytRepository $bhytRepository, 
        VienPhiRepository $vienPhiRepository, 
        DieuTriRepository $dieuTriRepository, 
        PhieuYLenhRepository $phieuYLenhRepository, 
        DanhMucDichVuRepository $danhMucDichVuRepository, 
        YLenhRepository $yLenhRepository, 
        PhongRepository $phongRepository, 
        HanhChinhRepository $hanhChinhRepository,
        ChuyenVienRepository $chuyenVienRepository,
        BenhVienRepository $benhVienRepository,
        noiGioiThieuRepository $noiGioiThieuRepository,
        InputLogSqsRepository $sqsRepository,
        DangKyKhamBenhErrorLog $errorLog
    )
    {
        // Services
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->vienPhiService = $vienPhiService;
        
        // Repositories
        $this->benhNhanRepository = $benhNhanRepository;
        $this->hsbaRepository = $hsbaRepository;
        $this->hsbaDonViRepository = $hsbaDonViRepository;
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
        $this->bhytRepository = $bhytRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->dieuTriRepository = $dieuTriRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->danhMucDichVuRepository = $danhMucDichVuRepository;
        $this->yLenhRepository = $yLenhRepository;
        $this->phongRepository = $phongRepository;
        $this->hanhChinhRepository = $hanhChinhRepository;
        $this->chuyenVienRepository = $chuyenVienRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->noiGioiThieuRepository = $noiGioiThieuRepository;
        $this->sqsRepository = $sqsRepository;
        $this->errorLog = $errorLog;
    }
    
    public function registerBenhNhan(Request $request)
    {
        //1. Kiểm tra thông tin bảo hiểm
        //2. Nếu có bảo hiểm thì bệnh nhân này đã tồn tại không tạo mới thông tin bệnh nhân
        //3. Nếu ko tìm thấy bảo hiểm tạo mới thông tin bệnh nhân
        //4. Tạo Hsba, hsba_khoa_phong, vien_phi, dieu_tri, phieu_y_lenh, y_lenh
        //kiểm tra thông tin scan
        $scan = $request->only('scan');
        //return $idBenhNhan;
        $arrayRequest = $request->all();

        $this->dataNgheNghiep = $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('nghe_nghiep', $request['nghe_nghiep_id']);
        $this->dataDanToc =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('dan_toc', $request['dan_toc_id']);
        $this->dataQuocTich =  $this->danhMucTongHopRepository->getTenDanhMucTongHopByKhoaGiaTri('quoc_tich', $request['quoc_tich_id']);
        $this->dataTinh = $this->hanhChinhRepository->getDataTinhById($request['tinh_thanh_pho_id']);
        $this->dataHuyen = $this->hanhChinhRepository->getDataHuyenById($request['tinh_thanh_pho_id'],$request['quan_huyen_id']);
        $this->dataXa = $this->hanhChinhRepository->getDataXaById($request['tinh_thanh_pho_id'],$request['quan_huyen_id'],$request['phuong_xa_id']);
        
        array_map(
            function ($k,$data) { 
                if (empty($data)){
                    //TODO - Logging Not found Data, set to OTHER ID
                    throw new \InvalidArgumentException($k." Not found");
                }
            },
            [
                'dataNgheNghiep','dataDanToc','dataQuocTich'
            ],
            [
                $this->dataNgheNghiep,
                $this->dataDanToc,
                $this->dataQuocTich
            ]
        );
        
        $this->dataNhomNguoiThan = new NhomNguoiThan($arrayRequest['loai_nguoi_than'], $arrayRequest['ten_nguoi_than'], $arrayRequest['dien_thoai_nguoi_than']);
        
        //set params benh_nhan 
        $benhNhanParams = $request->only(...$this->benhNhanKeys);
        $hsbaParams = $request->only(...$this->hsbaKeys);
        $hsbaDvParams = $request->only(...$this->hsbaDvKeys); 
        $hsbaKpParams = $request->only(...$this->hsbaKpKeys);
        $bhytParams = $request->only(...$this->bhytKeys);
        
        $bhytParams['image_url'] = $request->only('image_url_bhyt')['image_url_bhyt'];     
        $dieuTriParams = $request->only(...$this->dieuTriKeys);        
        $sttPhongKhamParams =  $request->only(...$this->sttPkKeys);
        $chuyenVienParams =  $request->only(...$this->chuyenVienKeys);
        
        $benhNhanParams['id'] = $this->checkBenhNhanTonTai($benhNhanParams['benh_nhan_id'],$benhNhanParams['ho_va_ten'],$benhNhanParams['ngay_sinh'],$benhNhanParams['gioi_tinh_id'], $scan);
        
        $result = DB::transaction(function () use ($request, $scan, $benhNhanParams, $hsbaParams,$hsbaDvParams, $hsbaKpParams, $bhytParams, $dieuTriParams, $sttPhongKhamParams,$chuyenVienParams, $arrayRequest) {
            try {
                // TODO - implement try catch log inside each function carefully
                $this->checkOrCreateBenhNhan($scan,$benhNhanParams)
                    ->createBhyt($bhytParams)
                    ->createHsbaKhamBenh($hsbaParams)
                    ->createHsbaDvKhamBenh($hsbaDvParams)
                    //->createHsbaKpKhamBenh($hsbaKpParams)
                    ->createChuyenVien($chuyenVienParams)
                    ->getDataYeucauKham()
                    ->getSttPhongKham($sttPhongKhamParams) //sothutuphongkham //error
                    ->createVienPhi()
                    ->updateHsba()
                    ->updateHsbaDv()
                    //->updateHsbaKp()
                    ->updateVienPhi()
                    ->createDieuTri()
                    ->createPhieuYLenh()
                    ->createYLenh()
                    ->pushToHsbaKpQueue()
                    ->pushLogQueue($arrayRequest, self::TYPE_LOG_INPUT);
                DB::commit();
                return $this->dataSttPk;
                
            } catch(\Throwable  $ex) {
                //store log
                DB::rollback();
                $this->exceptionToLog($request, $ex);
                throw $ex;
            } catch (\Exception $ex) {
                //store log
                DB::rollback();
                $this->exceptionToLog($request, $ex);
                throw $ex;
            } 
        });
        return $result;
    }
    
    private function createBhyt($params) {
        if($params['ms_bhyt'] != null && $params['tu_ngay'] != null && $params['den_ngay'] != null) {
            $dataBhyt = $params;
            $params['benh_nhan_id'] = $this->dataBenhNhan['id'];
            $dataBhyt['id'] = $this->bhytRepository->createDataBhyt($params);
        } else {
            $dataBhyt['id'] = null;
            $dataBhyt['ms_bhyt'] = null;
        }
        $this->dataBhyt = $dataBhyt;
        return $this;
    }
    
    private function checkOrCreateBenhNhan($scan,$params) {
        $tenBenhNhanInHoa = mb_convert_case($params['ho_va_ten'], MB_CASE_UPPER, "UTF-8");
        $dataBenhNhan = $params;
        
        $hinh_benh_nhan = $dataBenhNhan['hinh_benh_nhan'];
        unset($dataBenhNhan['hinh_benh_nhan']);
        if (empty($dataBenhNhan['benh_vien_id'])) $dataBenhNhan['benh_vien_id'] = 1;
            $dataBenhVienThietLap = $this->benhVienRepository->getBenhVienThietLap($dataBenhNhan['benh_vien_id']);
        unset($dataBenhNhan['benh_vien_id']);
            
        $dataBenhNhan['ho_va_ten'] = trim($tenBenhNhanInHoa);
        $dataBenhNhan['nghe_nghiep_id'] = ($this->dataNgheNghiep['gia_tri'])??null;
        $dataBenhNhan['dan_toc_id'] = $this->dataDanToc['gia_tri']??null;
        $dataBenhNhan['quoc_tich_id'] = $this->dataQuocTich['gia_tri']??null;
        $dataBenhNhan['nam_sinh'] =  str_limit($dataBenhNhan['ngay_sinh'], 4,'');// TODO - define constant
        $dataBenhNhan['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        // Khoi tao vi dien tu
        $newEncrypter = new \Illuminate\Encryption\Encrypter(env('APP_SECRET'));
        //$dataBenhNhan['vi_dien_tu'] = Crypt::encryptString(self::VI_DIEN_TU_MAC_DINH);
        $dataBenhNhan['vi_dien_tu'] = $newEncrypter->encrypt(self::VI_DIEN_TU_MAC_DINH);
        //$dataBenhNhan['thong_tin_chuyen_tuyen'] = !empty($dataBenhNhan['thong_tin_chuyen_tuyen']) ? json_encode($dataBenhNhan['thong_tin_chuyen_tuyen']) : null;
        
        unset($dataBenhNhan['benh_nhan_id']);
        if(!$dataBenhNhan['id'])
            $dataBenhNhan['id'] =  $this->benhNhanRepository->createDataBenhNhan($dataBenhNhan);
        $this->dataBenhNhan = $dataBenhNhan;
        if($hinh_benh_nhan) {
            $s3 = new AwsS3($dataBenhVienThietLap['bucket']);
            $image_parts = explode(";base64,", $hinh_benh_nhan);
            $dataHinhAnh = [];
            if(count($image_parts)>1)
            {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1], true);
            
                $filePath = 'storage/dang-ky-kham-benh/' . env('APP_ENV') . '/' . date("Y/m/d") . '/' . $dataBenhNhan['id'] . '.' . $image_type;
                
                $result = $s3->putObjectBase64($filePath, $image_base64, "image/".$image_type);
                $url_hinh_anh = 'https://s3-'. env('S3_REGION') .'.amazonaws.com/' .$dataBenhVienThietLap['bucket']. '/' . $filePath;
                
                $dataHinhAnh["url_hinh_anh"] = $url_hinh_anh;
            }
            else 
            {
                $dataHinhAnh["url_hinh_anh"] = $hinh_benh_nhan;
            }
            $this->benhNhanRepository->update($dataBenhNhan['id'], $dataHinhAnh);
            //define('UPLOAD_DIR', "./images");
            //$file = UPLOAD_DIR . uniqid() . '.png';
            //$file = UPLOAD_DIR . '.png';
            //file_put_contents($file, $image_base64);
            
        }
        return $this;
    }
    
    private function createHsbaKhamBenh($params) {
        $dataHsba = $params;
        $dataHsba['loai_benh_an'] = 24;// TODO - define constant
        $dataHsba['hinh_thuc_vao_vien'] = 2;// TODO - define constant
        $dataHsba['trang_thai_hsba'] = 0;// TODO - define constant
        $dataHsba['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataHsba['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $dataHsba['ten_benh_nhan_khong_dau'] = Util::convertViToEn($this->dataBenhNhan['ho_va_ten']);
        $dataHsba['is_dang_ky_truoc'] = 0;// TODO - define constant
        $dataHsba['ten_nghe_nghiep'] = $this->dataNgheNghiep['dien_giai']??null;
        $dataHsba['ten_dan_toc'] = $this->dataDanToc['dien_giai']??null;
        $dataHsba['ten_quoc_tich'] = $this->dataQuocTich['dien_giai']??null;
        $dataHsba['nghe_nghiep_id'] = $this->dataNgheNghiep['gia_tri']??null;
        $dataHsba['dan_toc_id'] = $this->dataDanToc['gia_tri']??null;
        $dataHsba['quoc_tich_id'] = $this->dataQuocTich['gia_tri']??null;
        //chưa xử id
        $dataHsba['ten_tinh_thanh_pho'] = $this->dataTinh['ten_tinh']??null;
        $dataHsba['ten_quan_huyen'] = $this->dataHuyen['ten_huyen']??null;
        $dataHsba['ten_phuong_xa'] = $this->dataXa['ten_xa']??null;
        $dataHsba['nam_sinh'] =  $this->dataBenhNhan['nam_sinh'];
        $dataHsba['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
        $dataHsba['ngay_tao'] = Carbon::now()->toDateTimeString();
        $dataHsba['ghi_chu'] = $dataHsba['noi_gioi_thieu_khac'];
        unset($dataHsba['noi_gioi_thieu_khac']);
        if($dataHsba['loai_gioi_thieu'] == self::LOAI_GIOI_THIEU_KHAC)
        {
            $dataNoiGioiThieu = [];
            $dataNoiGioiThieu["ten"] = $dataHsba['ghi_chu'];
            $dataNoiGioiThieu["loai"] = self::LOAI_NOI_GIOI_THIEU_KHONG_HUE_HONG;
            $dataHsba['noi_gioi_thieu_id'] = $this->noiGioiThieuRepository->create($dataNoiGioiThieu);
        }
        unset($dataHsba['loai_gioi_thieu']);
        //$dataHsba['thong_tin_chuyen_tuyen'] = !empty($dataHsba['thong_tin_chuyen_tuyen']) ? json_encode($dataHsba['thong_tin_chuyen_tuyen']) : null;
        //var_dump($dataHsba);
        //print_r($dataHsba);die();
        $dataHsba['id'] = $this->hsbaRepository->createDataHsba($dataHsba);
        $this->dataHsba = $dataHsba;
        return $this;
    }
    
    private function createHsbaDvKhamBenh($params) {
        $dataHsbaDv = $params;
        $dataHsbaDv['hsba_id'] = $this->dataHsba['id'];
        $dataHsbaDv['trang_thai'] = 0;
        $dataHsbaDv['khoa_hien_tai'] = $this->dataHsba['khoa_id'];
        $dataHsbaDv['loai_benh_an'] = 24;
        $dataHsbaDv['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataHsbaDv['bhyt_id'] = $this->dataBhyt['id'];
        $dataHsbaDv['thoi_gian_vao_vien'] = Carbon::now()->toDateTimeString();
        $dataHsbaDv['hinh_thuc_vao_vien_id'] = 2;
        $dataHsbaDv['id'] = $this->hsbaDonViRepository->create($dataHsbaDv);
        $this->dataHsbaDv = $dataHsbaDv;
        return $this;
    }    
    
    private function createHsbaKpKhamBenh($params) {
        //set params hsba_khoa_phong
        $dataHsbaKp = array_except($params, ['loai_vien_phi']);
        $dataHsbaKp['khoa_hien_tai'] = $this->dataHsba['khoa_id'];
        $dataHsbaKp['hsba_id'] = $this->dataHsba['id'];
        $dataHsbaKp['trang_thai'] = 0;// TODO - define constant
        $dataHsbaKp['loai_benh_an'] = 24;// TODO - define constant
        $dataHsbaKp['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataHsbaKp['hinh_thuc_vao_vien_id'] = 2;// TODO - define constant
        $dataHsbaKp['bhyt_id'] = $this->dataBhyt['id'];
        $dataHsbaKp['thoi_gian_vao_vien'] = Carbon::now()->toDateTimeString();
        //insert hsba_khoa_phong
        $dataHsbaKp['id'] = $this->hsbaKhoaPhongRepository->createData($dataHsbaKp);
        $dataHsbaKp['loai_vien_phi'] = $params['loai_vien_phi'];
        $this->dataHsbaKp = $dataHsbaKp;
        return $this;
    }
    
    private function createChuyenVien($params) {
        //set params chuyen_vien
        //$dataChuyenVien = $params;
        if(count($params)>1){
            $dataChuyenVien['khoa_id'] = $params['khoa_id'];
            //$dataChuyenVien['hsba_khoa_phong_id'] = $this->dataHsbaKp['id']?$this->dataHsbaKp['id']:null;
            $dataChuyenVien['hsba_don_vi_id'] = $this->dataHsbaDv['id']?$this->dataHsbaDv['id']:null;
            $dataChuyenVien['benh_nhan_id'] = $this->dataBenhNhan['id']?$this->dataBenhNhan['id']:null;
            $dataChuyenVien['thoi_gian_chuyen_vien'] = isset($params['chuyen_tuyen_hoi'])?$params['chuyen_tuyen_hoi']:null;
            $dataChuyenVien['ma_benh_vien_chuyen_toi'] = isset($params['benh_vien_tuyen_duoi_code'])?$params['benh_vien_tuyen_duoi_code']:null;
            $dataChuyenVien['tinh_trang_nguoi_benh'] = isset($params['tinh_trang_chuyen_tuyen'])?$params['tinh_trang_chuyen_tuyen']:null;
            $dataChuyenVien['phuong_tien_van_chuyen'] = isset($params['phuong_tien_van_chuyen'])?$params['phuong_tien_van_chuyen']:null;
            $dataChuyenVien['nguoi_van_chuyen'] = isset($params['ten_nguoi_ho_tong'])?$params['ten_nguoi_ho_tong']:null;
            $dataChuyenVien['dau_hieu_lam_sang'] = isset($params['dau_hieu_lam_sang'])?$params['dau_hieu_lam_sang']:null;
            $dataChuyenVien['thuoc'] = isset($params['phuong_phap_thu_thuat'])?$params['phuong_phap_thu_thuat']:null;
            $dataChuyenVien['xet_nghiem'] = isset($params['ket_qua_xet_nghiem'])?$params['ket_qua_xet_nghiem']:null;
            $dataChuyenVien['huong_dieu_tri'] = isset($params['huong_dieu_tri'])?$params['huong_dieu_tri']:null;
            $dataChuyenVien['chan_doan'] = isset($params['chan_doan'])?$params['chan_doan']:null;
            $dataChuyenVien['ly_do_chuyen_vien_id'] = isset($params['ly_do_chuyen_tuyen'])?$params['ly_do_chuyen_tuyen']:null;
            $dataChuyenVien['chan_doan_tuyen_duoi_code'] = isset($params['chan_doan_tuyen_duoi_code'])?$params['chan_doan_tuyen_duoi_code']:null;
            $dataChuyenVien['chan_doan_tuyen_duoi_text'] = isset($params['chan_doan_tuyen_duoi'])?$params['chan_doan_tuyen_duoi']:null;
            //insert chuyen_vien
            $dataChuyenVien['id'] = $this->chuyenVienRepository->createData($dataChuyenVien);
            $this->dataChuyenVien = $dataChuyenVien;
            return $this;
        }
        else
            return $this;
    }    
    
    private function getSttPhongKham($params) {
        $sttPhongKhamParams = $params;
        $sttPhongKhamParams['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $sttPhongKhamParams['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $sttPhongKhamParams['gioi_tinh_id'] = $this->dataBenhNhan['gioi_tinh_id'];
        $sttPhongKhamParams['ms_bhyt'] = $this->dataHsba['ms_bhyt'];
        $sttPhongKhamParams['yeu_cau_kham'] = $this->dataYeuCauKham['ten'];
        $sttPhongKhamParams['khoa_id'] = $this->dataHsba['khoa_id'];
        $sttPhongKhamParams['benh_vien_id'] = $this->dataHsba['benh_vien_id'];
        $sttPhongKhamParams['hsba_id'] = $this->dataHsba['id'];
        //$sttPhongKhamParams['hsba_khoa_phong_id'] = $this->dataHsbaKp['id'];
        $sttPhongKhamParams['hsba_don_vi_id'] = $this->dataHsbaDv['id'];
        $dataSttPhongKham = $this->sttPhongKhamService->getSttPhongKham($sttPhongKhamParams);
        $this->dataSttPk = $dataSttPhongKham;
        return $this;
    }
    
    private function createVienPhi() {
        //set params vien_phi
        // $dataVienPhi['loai_vien_phi'] = $this->dataHsbaKp['doi_tuong_benh_nhan'] == 1 ? 2 : 1;// TODO - define constant
        $dataVienPhi['loai_vien_phi'] = $this->dataHsbaDv['loai_vien_phi'];
        $dataVienPhi['trang_thai'] = 0;// TODO - define constant
        $dataVienPhi['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataVienPhi['doi_tuong_benh_nhan'] = $this->dataHsbaDv['doi_tuong_benh_nhan'];
        $dataVienPhi['bhyt_id'] = $this->dataBhyt['id'];
        $dataVienPhi['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataVienPhi['hsba_id'] = $this->dataHsba['id'];
        $dataVienPhi['trang_thai_thanh_toan_bh'] = 0;// TODO - define constant
        //insert vien_phi
        $dataVienPhi['id'] = $this->vienPhiRepository->createDataVienPhi($dataVienPhi);
        $this->dataVienPhi = $dataVienPhi;
        return $this;
    }
    
    private function createDieuTri() {
        //set params dieu_tri
        // $dataDieuTri['hsba_khoa_phong_id'] = $this->dataHsbaKp['id'];
        $dataDieuTri['hsba_don_vi_id'] = $this->dataHsbaDv['id'];
        $dataDieuTri['hsba_id'] = $this->dataHsba['id'];
        $dataDieuTri['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataDieuTri['phong_id'] = $this->dataSttPk['phong_id'];
        $dataDieuTri['auth_users_id'] = $this->dataHsba['auth_users_id'];
        $dataDieuTri['benh_nhan_id'] =  $this->dataBenhNhan['id'];
        $dataDieuTri['ten_benh_nhan'] = $this->dataBenhNhan['ho_va_ten'];
        $dataDieuTri['nam_sinh'] = str_limit($this->dataBenhNhan['ngay_sinh'], 4,'');// TODO - define constant
        $dataDieuTri['gioi_tinh_id'] = $this->dataBenhNhan['gioi_tinh_id'];
        //insert dieu_tri
        $dataDieuTri['id'] = $this->dieuTriRepository->createDataDieuTri($dataDieuTri);
        $this->dataDieuTri = $dataDieuTri;
        return $this;
    }
    
    private function createPhieuYLenh() {
        //set params phieu_y_lenh
        $dataPhieuYLenh['benh_nhan_id'] = $this->dataBenhNhan['id'];
        $dataPhieuYLenh['vien_phi_id'] = $this->dataVienPhi['id'];
        $dataPhieuYLenh['hsba_id'] = $this->dataHsba['id'];
        $dataPhieuYLenh['dieu_tri_id'] = $this->dataDieuTri['id'];
        $dataPhieuYLenh['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataPhieuYLenh['phong_id'] = $this->dataSttPk['phong_id'];
        $dataPhieuYLenh['auth_users_id'] = $this->dataHsba['auth_users_id'];
        $dataPhieuYLenh['loai_phieu_y_lenh'] = 2; // TODO - define constant
        $dataPhieuYLenh['trang_thai'] = 0; // TODO - define constant
        $dataPhieuYLenh['id'] = $this->phieuYLenhRepository->getPhieuYLenhId($dataPhieuYLenh);
        $this->dataPhieuYLenh = $dataPhieuYLenh;
        return $this;
    }
    
    private function createYLenh() {
        //tính bhyt, viện phí
        if($this->dataHsba['ms_bhyt']) {
            $input['ms_bhyt'] = $this->dataHsba['ms_bhyt'];
            $mucHuong = $this->vienPhiService->getMucHuong($input);
        } else {
            $mucHuong = 0;
        }
        
        $bhytTra = $mucHuong * (int)$this->dataYeuCauKham['gia_bhyt'];
        $vienPhi = (1 - $mucHuong) * (int)$this->dataYeuCauKham['gia_bhyt'] + (int)$this->dataYeuCauKham['gia'] - (int)$this->dataYeuCauKham['gia_bhyt'];
        
        //set params y_lenh
        $dataYLenh['vien_phi_id'] = $this->dataVienPhi['id'];
        $dataYLenh['phieu_y_lenh_id'] = $this->dataPhieuYLenh['id'];
        $dataYLenh['doi_tuong_benh_nhan'] = $this->dataHsbaDv['doi_tuong_benh_nhan'];
        $dataYLenh['khoa_id'] = $this->dataHsba['khoa_id'];
        $dataYLenh['phong_id'] = $this->dataSttPk['phong_id'];
        $dataYLenh['ma'] = $this->dataYeuCauKham['ma'];
        $dataYLenh['ten'] = $this->dataYeuCauKham['ten'];
        $dataYLenh['ten_bhyt'] = $this->dataYeuCauKham['ten_bhyt'];
        $dataYLenh['ten_nuoc_ngoai'] = $this->dataYeuCauKham['ten_nuoc_ngoai'];
        $dataYLenh['trang_thai'] = 0; // TODO - define constant
        $dataYLenh['gia'] = (double)$this->dataYeuCauKham['gia'];
        $dataYLenh['gia_bhyt'] = (double)$this->dataYeuCauKham['gia_bhyt'];
        $dataYLenh['gia_nuoc_ngoai'] = (double)$this->dataYeuCauKham['gia_nuoc_ngoai'];
        $dataYLenh['loai_y_lenh'] = 1; // TODO - define constant
        $dataYLenh['thoi_gian_chi_dinh'] = Carbon::now()->toDateTimeString();
        $dataYLenh['muc_huong'] = $mucHuong;
        $dataYLenh['bhyt_tra'] = $bhytTra;
        $dataYLenh['vien_phi'] = $vienPhi;
        $dataYLenh['so_luong'] = 1;
        $dataYLenh['loai_thanh_toan_cu'] = $this->dataHsbaDv['loai_vien_phi'];
        $dataYLenh['loai_thanh_toan_moi'] = $this->dataHsbaDv['loai_vien_phi'];
        $dataYLenh['ms_bhyt'] = $this->dataHsba['ms_bhyt'] ?? null;
        $dataYLenh['id'] = $this->yLenhRepository->createDataYLenh($dataYLenh);
        $this->dataYLenh = $dataYLenh;
        return $this;
    }
    
    private function checkBhytFromScanner($scan) {
        $msBhyt = trim($scan['scan']);
        if($this->isBHYTNumber($msBhyt))//thẻ bhyt
        {
            $checkedBhyt = $this->bhytRepository->checkMaSoBhyt($msBhyt);
        } else {
            return null;
        }
    }
    
    private function isBHYTNumber($value) {
        return strlen($value) == 15;
    }
    
    private function pushToHsbaKpQueue() {
        
        $benhVienId = $this->dataHsba['benh_vien_id'];
        $khoaId = $this->dataHsba['khoa_id'];
        $phongId = $this->dataSttPk['phong_id'];
        $ngayVaoVien = Carbon::now()->toDateString();
        $this->hsbaKhoaPhongService ->setQueueAttribute($benhVienId, $khoaId, $phongId, $ngayVaoVien)
                                    ->setQueueBody([
                                            'benh_vien_id' => $this->dataHsba['benh_vien_id'],
                                            'hsba_id' => $this->dataHsba['id'], 
                                            'hsba_khoa_phong_id' => $this->dataHsbaDv['id'],
                                            'ten_benh_nhan' => $this->dataBenhNhan['ho_va_ten'], 
                                            'nam_sinh' => $this->dataBenhNhan['nam_sinh'], 
                                            'ms_bhyt' => $this->dataBhyt['ms_bhyt'], 
                                            'trang_thai_hsba' => $this->dataHsba['trang_thai_hsba'],
                                            'ngay_tao' => $this->dataHsba['ngay_tao'], // Modify repository
                                            'ngay_ra_vien' => '', // Modify repository
                                            'thoi_gian_vao_vien' => $this->dataHsbaDv['thoi_gian_vao_vien'], 
                                            'thoi_gian_ra_vien' => '',
                                            'trang_thai_cls' => '', 
                                            'ten_trang_thai_cls' => '',
                                            'trang_thai' => $this->dataHsbaDv['trang_thai'], 
                                            'ten_trang_thai' => '' // TODO - get Ten Trang Thai CLS
                                        ])
                                    ->pushToQueue();
        return $this;
    }    
    
    // private function setDataTHX($params) {
    //     $this->dataTenTHX = Util::getDataFromGooglePlace($this->dataTHX);
    //     $this->dataTinh = $this->hanhChinhRepository->getDataTinh(mb_convert_case($this->dataTenTHX['ten_tinh_thanh_pho'], MB_CASE_UPPER, "UTF-8"));
    //     $this->dataHuyen = $this->hanhChinhRepository->getDataHuyen($this->dataTinh['ma_tinh'], mb_convert_case($this->dataTenTHX['ten_quan_huyen'], MB_CASE_UPPER, "UTF-8"));
    //     $this->dataXa = $this->hanhChinhRepository->getDataXa($params['tinh_thanh_pho_id'], $params['quan_huyen_id'], $params['phuong_xa_id']);
    // }
    
    private function getDataYeucauKham(){
         $this->dataYeuCauKham = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($this->dataHsbaDv['yeu_cau_kham_id']);
         return $this;
    }
    
    private function updateHsba(){
        //update phong_id từ stt_phong_kham
        //$this->hsbaRepository->updateHsba($idHsba, $thxData);
        $this->hsbaRepository->updateHsba($this->dataHsba['id'], ['phong_id' => $this->dataSttPk['phong_id']]);
        return $this;
    }
    
    private function updateHsbaDv(){
        $this->hsbaDonViRepository->update($this->dataHsbaDv['id'], ['phong_hien_tai' => $this->dataSttPk['phong_id'], 'vien_phi_id' => $this->dataVienPhi['id']]);
        return $this;
    }    
    
    private function updateHsbaKp(){
        $this->hsbaKhoaPhongRepository->update($this->dataHsbaKp['id'], ['phong_hien_tai' => $this->dataSttPk['phong_id'], 'vien_phi_id' => $this->dataVienPhi['id']]);
        return $this;
    }
    
    private function updateVienPhi(){
        $this->vienPhiRepository->updateVienPhi($this->dataVienPhi['id'], ['phong_id' => $this->dataSttPk['phong_id']]);
        return $this;
    }
    
    private function getMucHuong()
    {
        
    }
    
    private function pushLogQueue($message, $type) {
        $bucketS3 = $this->getBucketS3ByBenhVienId($message['benh_vien_id']); 
        
        $messageAttributes = [
            'benh_vien_id' => ['DataType' => "Number",
                                'StringValue' => $message['benh_vien_id']
                            ],
            'khoa_id'   => ['DataType' => "Number",
                                'StringValue' => $message['khoa_id']
                            ],
            'phong_id'  => ['DataType' => "Number",
                                'StringValue' => $message['phong_id']
                            ],
            'bucket'    => ['DataType' => "String",
                                'StringValue' => $bucketS3
                            ],
            'app_env'    => ['DataType' => "String",
                                'StringValue' => env('APP_ENV')
                            ],
            'type_log'    => ['DataType' => "String",
                                'StringValue' => $type
                            ]
        ];
        
        try {
        // Push
            $this->sqsRepository->push(
                $messageAttributes, $message
            );
        } catch ( \Exception $ex) {
            throw $ex;
        }
    }

    private function getBucketS3ByBenhVienId($benhVienId) {
        $data = $this->benhVienRepository->getBenhVienThietLap($benhVienId);
        return $data['bucket'];
    }
    
    private function exceptionToLog($params, $ex) {
        $bucketS3 = $this->getBucketS3ByBenhVienId($params['benh_vien_id']);
        $this->errorLog->setBucketS3($bucketS3);
        $this->errorLog->setFolder('dkkb');
        $messageAttributes = [
            'key'    => ['DataType' => "String",
                'StringValue' => $params['ho_va_ten']
            ],
        ];
        $this->errorLog->toLogQueue($params, $ex, $messageAttributes);
    }
    
    private function checkBenhNhanTonTai($idBenhNhan, $tenBenhNhan, $ngaySinh, $gioiTinh, $scan) {
        $return = 0;
        if($idBenhNhan){
            $result = $this->benhNhanRepository->checkBenhNhanTonTai($idBenhNhan, $tenBenhNhan, $ngaySinh, $gioiTinh);
            if($result)
                $return = $result->id;
        }
        else{
            $bhyt = $this->checkBhytFromScanner($scan);
            if ($bhyt['benh_nhan_id']) {
                $return = $bhyt['benh_nhan_id'];
            }
        }
        return $return;
    }
}