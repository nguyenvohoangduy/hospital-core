<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HsbaKhoaPhongResource extends Resource
{
    public function toArray($request)
    {
        return [
            'medicalrecordid'               => $this->medicalrecordid,
            'medicalrecordcode'             => $this->medicalrecordcode,
            'sothutuid'                     => $this->sothutuid,
            'sothutunumber'                 => $this->sothutunumber,
            'sothutuphongkhamid'            => $this->sothutuphongkhamid,
            'sothutuphongkhamnumber'        => $this->sothutuphongkhamnumber,
            'vienphiid'                     => $this->vienphiid,
            'hosobenhanid'                  => $this->hosobenhanid,
            'medicalrecordid_next'          => $this->medicalrecordid_next,
            'medicalrecordid_master'        => $this->medicalrecordid_master,
            'medicalrecordstatus'           => $this->medicalrecordstatus,
            'departmentgroupid'             => $this->departmentgroupid,
            'departmentid'                  => $this->departmentid,
            'giuong'                        => $this->giuong,
            'loaibenhanid'                  => $this->loaibenhanid,
            'userid'                        => $this->userid,
            'patientid'                     => $this->patientid,
            'doituongbenhnhanid'            => $this->doituongbenhnhanid,
            'bhytid'                        => $this->bhytid,
            'lydodenkham'                   => $this->lydodenkham,
            'yeucaukham'                    => $this->yeucaukham,
            'thoigianvaovien'               => $this->thoigianvaovien,
            'chandoanvaovien'               => $this->chandoanvaovien,
            'chandoantuyenduoi'             => $this->chandoantuyenduoi,
            'chandoantuyenduoi_code'        => $this->chandoantuyenduoi_code,
            'noigioithieucode'              => $this->noigioithieucode,
            'chandoanvaovien_code'          => $this->chandoanvaovien_code,
            'chandoanvaovien_kemtheo'       => $this->chandoanvaovien_kemtheo,
            'chandoanvaovien_kemtheo_code'  => $this->chandoanvaovien_kemtheo_code,
            'chandoankkb'                   => $this->chandoankkb,
            'chandoankkb_code'              => $this->chandoankkb_code,
            'chandoanvaokhoa'               => $this->chandoanvaokhoa,
            'chandoanvaokhoa_code'          => $this->chandoanvaokhoa_code,
            'chandoanvaokhoa_kemtheo'       => $this->chandoanvaokhoa_kemtheo,
            'chandoanvaokhoa_kemtheo_code'  => $this->chandoanvaokhoa_kemtheo_code,
            'isthuthuat'                    => $this->isthuthuat,
            'isphauthuat'                   => $this->isphauthuat,
            'hinhthucvaovienid'             => $this->hinhthucvaovienid,
            'backdepartmentid'              => $this->backdepartmentid,
            'uutienkhamid'                  => $this->uutienkhamid,
            'noigioithieuid'                => $this->noigioithieuid,
            'vaoviencungbenhlanthu'         => $this->vaoviencungbenhlanthu,
            'thoigianravien'                => $this->thoigianravien,
            'chandoanravien'                => $this->chandoanravien,
            'chandoanravien_code'           => $this->chandoanravien_code,
            'chandoanravien_kemtheo'        => $this->chandoanravien_kemtheo,
            'chandoanravien_kemtheo_code'   => $this->chandoanravien_kemtheo_code,
            'chandoanravien_kemtheo1'       => $this->chandoanravien_kemtheo1,
            'chandoanravien_kemtheo_code1'  => $this->chandoanravien_kemtheo_code1,
            'chandoanravien_kemtheo2'       => $this->chandoanravien_kemtheo2,
            'chandoanravien_kemtheo_code2'  => $this->chandoanravien_kemtheo_code2,
            'xutrikhambenhid'               => $this->xutrikhambenhid,
            'hinhthucravienid'              => $this->hinhthucravienid,
            'ketquadieutriid'               => $this->ketquadieutriid,
            'nextdepartmentid'              => $this->nextdepartmentid,
            'nexthospitalid'                => $this->nexthospitalid,
            'istaibien'                     => $this->istaibien,
            'isbienchung'                   => $this->isbienchung,
            'giaiphaubenhid'                => $this->giaiphaubenhid,
            'lydovaovien'                   => $this->lydovaovien,
            'vaongaythucuabenh'             => $this->vaongaythucuabenh,
            'quatrinhbenhly'                => $this->quatrinhbenhly,
            'tiensubenh_banthan'            => $this->tiensubenh_banthan,
            'tiensubenh_giadinh'            => $this->tiensubenh_giadinh,
            'khambenh_toanthan'             => $this->khambenh_toanthan,
            'khambenh_mach'                 => $this->khambenh_mach,
            'khambenh_nhietdo'              => $this->khambenh_nhietdo,
            'khambenh_huyetap_low'          => $this->khambenh_huyetap_low,
            'khambenh_huyetap_high'         => $this->khambenh_huyetap_high,
            'khambenh_nhiptho'              => $this->khambenh_nhiptho,
            'khambenh_cannang'              => $this->khambenh_cannang,
            'khambenh_chieucao'             => $this->khambenh_chieucao,
            'khambenh_vongnguc'             => $this->khambenh_vongnguc,
            'khambenh_vongdau'              => $this->khambenh_vongdau,
            'khambenh_bophan'               => $this->khambenh_bophan,
            'tomtatkqcanlamsang'            => $this->tomtatkqcanlamsang,
            'chandoanbandau'                => $this->chandoanbandau,
            'daxuly'                        => $this->daxuly,
            'tomtatbenhan'                  => $this->tomtatbenhan,
            'chandoankhoakhambenh'          => $this->chandoankhoakhambenh,
            'daxulyotuyenduoi'              => $this->daxulyotuyenduoi,
            'medicalrecordremark'           => $this->medicalrecordremark,
            'lastaccessdate'                => $this->lastaccessdate,
            'canlamsangstatus'              => $this->canlamsangstatus,
            'version'                       => $this->version,
            'sync_flag'                     => $this->sync_flag,
            'update_flag'                   => $this->update_flag,
            'lastuserupdated'               => $this->lastuserupdated,
            'lasttimeupdated'               => $this->lasttimeupdated,
            'keylock'                       => $this->keylock,
            'cv_chuyenvien_hinhthucid'      => $this->cv_chuyenvien_hinhthucid,
            'cv_chuyenvien_lydoid'          => $this->cv_chuyenvien_lydoid,
            'cv_chuyendungtuyen'            => $this->cv_chuyendungtuyen,
            'cv_chuyenvuottuyen'            => $this->cv_chuyenvuottuyen,
            'xetnghiemcanthuchienlai'       => $this->xetnghiemcanthuchienlai,
            'loidanbacsi'                   => $this->loidanbacsi,
            'nextbedrefid'                  => $this->nextbedrefid,
            'nextbedrefid_nguoinha'         => $this->nextbedrefid_nguoinha,
            'chandoanbandau_code'           => $this->chandoanbandau_code,
            'thoigianchuyenden'             => $this->thoigianchuyenden,
            'khambenh_thilucmatphai'        => $this->khambenh_thilucmatphai,
            'khambenh_thilucmattrai'        => $this->khambenh_thilucmattrai,
            'khambenh_klthilucmatphai'      => $this->khambenh_klthilucmatphai,
            'khambenh_klthilucmattrai'      => $this->khambenh_klthilucmattrai,
            'khambenh_nhanapmatphai'        => $this->khambenh_nhanapmatphai,
            'khambenh_nhanapmattrai'        => $this->khambenh_nhanapmattrai
        ];
    }
}