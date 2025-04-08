<?php
include '../koneksi.php'; // Include file koneksi database
include '../vendor/autoload.php'; // Include PhpSpreadsheet melalui Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Folder upload
    $uploadDir = 'uploads/';
    $folderName = $uploadDir . 'Berkas_' . str_replace(' ', '_', $_POST['name_personal']);
    if (!file_exists($folderName)) {
        mkdir($folderName, 0777, true);
    }

    // Validasi dan upload file
    $files = [
        'surat_lamaran' => 'Surat Lamaran',
        'cv' => 'CV',
        'kk' => 'Kartu Keluarga',
        'ktp' => 'KTP',
        'pas_foto' => 'Pas Foto',
        'ijazah' => 'Ijazah',
        'supporting_doc' => 'Dokumen Pendukung'
    ];

    foreach ($files as $key => $label) {
        // Cek jika file diunggah
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$key]['tmp_name'];
            $fileName = $_FILES[$key]['name'];
            $fileSize = $_FILES[$key]['size'];
            $fileType = $_FILES[$key]['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Validasi ukuran file maksimal 4MB
            if ($fileSize > 4 * 1024 * 1024) {
                die("$label melebihi ukuran maksimal 4MB.");
            }

        // Validasi jenis file
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'docx']; // Tambahkan jenis file Word (docx)
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die("$label harus berupa file PDF, JPG, PNG, atau DOCX.");
        }

        // Simpan file dengan nama sesuai label
        $newFileName = $label . '_an_' . str_replace(' ', '_', $_POST['name_personal']) . '.' . $fileExtension;
        $filePath = $folderName . '/' . $newFileName;

        if (!move_uploaded_file($fileTmpPath, $filePath)) {
            die("Gagal mengupload $label.");
        }

        } else {
            die("$label tidak diunggah dengan benar.");
        }
    }

    // Data untuk Excel menggunakan PhpSpreadsheet
    $spreadsheet = new Spreadsheet();

// Sheet 1: KTP Passport
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('KTP Passport');
    $sheet1->fromArray([
        ['Nama Perusahaan', 'Action Type', 'PA','Personnel Area', 'Organization', 'Employee Group', 'Position', 'Employment Status', 'Gender Key', 'Religion', 'BirthPlace', 'Nationality', 'Martial Status Key', 'MS since', 'Ethnic origin', 'Birth date'],
        ['Mayawana Persada', $_POST['action_type'],$_POST['pa'], $_POST['personnel_area'], $_POST['organization'], $_POST['employee_group'], $_POST['position'], $_POST['employment_status'], $_POST['gender_key'], $_POST['religion'], $_POST['birthplace'], $_POST['nationality'], $_POST['martial_status'], $_POST['ms_since'], $_POST['ethnic_origin'], $_POST['birth_date']]
    ]);

// Sheet 2: Personal Data
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Personal Data');
    $sheet2->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'PT', 'Type of Identification', 'ID Number', 'Issue Date', 'ID ExpDate', 'Place Issue', 'Country of Issue'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['pt'], $_POST['id_type'], $_POST['id_number'], $_POST['issue_date'], $_POST['id_exp_date'], $_POST['place_issue'], $_POST['country_of_issue']]
    ]);
// Sheet 3: Address
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Address');
    $sheet2->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'Start date', 'End Date', 'Type', 'Address Record Type', 'Street and House Number', 'City', 'Postal Code', 'District', 'Telephone No'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['start_date'], $_POST['end_date'], $_POST['type'], $_POST['address_record_type'], $_POST['street_and_house_number'], $_POST['city'], $_POST['postal_code'], $_POST['district'],$_POST['telephone_no']]
    ]);
// Sheet 4: BPJS
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('BPJS ID');
    $sheet2->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'PT', 'Start date', 'End Date', 'BPJS ID', 'Number', 'Benefit Class for BPJS', 'BPJS Ketenagakerjaan', 'BPJS lama'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['pt'], $_POST['start_date'], $_POST['end_date'], $_POST['bpjs_id'], $_POST['number'], $_POST['benefit_class_for_bpjs'],$_POST['bpjs_ketenagakerjaan'],$_POST['bpjs_lama']]
    ]);
// sheet 5: NPWP
    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('NPWP');
    $sheet3->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'PT', 'TAX 2025', 'Start Date', 'End Date', 'TaxId', 'Date', 'TD', 'Martial Status of the Employee', 'Is Employee Entitled to Spouse'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['pt'], $_POST['tax_2025'], $_POST['start_date'], $_POST['end_date'], $_POST['tax_id'], $_POST['date'], $_POST['td'], $_POST['marital_status_of_the_employee'], $_POST['is_employee_entitled_to_spouse']]
    ]);

// sheet 6: Family Details
    $sheet4 = $spreadsheet->createSheet();
    $sheet4->setTitle('Family Details');
    $sheet4->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name of Employee', 'Memb*', 'Family Name', 'Birth Place', 'Religion', 'Nationality', 'Gender Key', 'Start Date', 'End Date', 'Birth Date'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['employee_name'], $_POST['memb'], $_POST['family_name'], $_POST['birth_place'], $_POST['religion'], $_POST['nationality'], $_POST['gender_key'], $_POST['start_date'], $_POST['end_date'], $_POST['birth_date']]
    ]);

// sheet 7: Education
    $sheet5 = $spreadsheet->createSheet();
    $sheet5->setTitle('Education');
    $sheet5->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'Start Date', 'End Date', 'Duration', 'Educational Establishment', 'Institute/Location', 'City', 'Certificate', 'Branch of Study'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['start_date'], $_POST['end_date'], $_POST['duration'], $_POST['educational_establishment'], $_POST['institute_location'], $_POST['city'], $_POST['certificate'], $_POST['branch_of_study']]
    ]);
// sheet 8: Int Working
    $sheet8 = $spreadsheet->createSheet();
    $sheet8->setTitle('Int Working');
    $sheet8->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'Start Date', 'End Date', 'Company', 'City','City Id', 'Position Held'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['start_date'], $_POST['end_date'], $_POST['company'], $_POST['city'],$_POST['city_id'], $_POST['position_held']]
    ]);

// sheet 9: EX Working
    $sheet9 = $spreadsheet->createSheet();
    $sheet9->setTitle('EX Working');
    $sheet9->fromArray([
        ['Nama Perusahaan', 'No', 'Pers.No', 'Name', 'Start Date', 'End Date', 'Company', 'City', 'City Id', 'Position Held'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['start_date'], $_POST['end_date'], $_POST['company'], $_POST['city'],$_POST['city_id'], $_POST['position_held']]
    ]);

// sheet 10: Bank Detail
    $sheet10 = $spreadsheet->createSheet();
    $sheet10->setTitle('Bank Detail');
    $sheet10->fromArray([
        ['Nama Perusahaan', 'No', 'Pers No', 'Name', 'PT', 'Bank Country', 'MWP & HMP {Bank Name, Bank Key/Branch/Address, Account Number}', 'WIA {Bank Name, Bank Key/Branch/Address, Account Number}', 'Payment Method', 'Payment Currency'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['pt'], $_POST['bank_country'], 
        $_POST['mwp_bank_name'], $_POST['mwp_bank_key_branch_address'], $_POST['mwp_account_number'], 
        $_POST['wia_bank_name'], $_POST['wia_bank_key_branch_address'], $_POST['wia_account_number'], 
        $_POST['payment_method'], $_POST['payment_currency']]
    ]);

// sheet 11: Training
    $sheet11 = $spreadsheet->createSheet();
    $sheet11->setTitle('Training');
    $sheet11->fromArray([
        ['Nama Perusahaan', 'No', 'Pers No', 'Name*', 'Bank Country*', 'Jenis Training*', 'Instansi/Penyelenggara*', 'Instruktur/Pembicara*', 'Kota/Negara*', 'Tahun Periode*'],
        ['Mayawana Persada', $_POST['no'], $_POST['pers_no'], $_POST['name_personal'], $_POST['bank_country'], 
        $_POST['jenis_training'], $_POST['instansi_penyelenggara'], $_POST['instruktur_pembicara'], 
        $_POST['kota_negara'], $_POST['tahun_periode']]
    ]);

// sheet 12: Language
    $sheet12 = $spreadsheet->createSheet();
    $sheet12->setTitle('Language');
    $sheet12->fromArray([
        ['Nama Perusahaan', 'Language', 'Read {Unable, Limited, Good}', 'Write {Unable, Limited, Good}', 'Speak {Unable, Limited, Good}', 'Komputer Literacy {Limited, Good, Very Good}'],
        ['Mayawana Persada', $_POST['language'], $_POST['read'], $_POST['write'], $_POST['speak'], $_POST['computer_literacy']]
    ]);

    // Menambahkan centang di bagian "Read", "Write", dan "Speak" jika statusnya "Good" atau "Limited" sesuai dengan pilihan yang dikirimkan
    // Contoh implementasi centang (checkbox) untuk kolom Read, Write, Speak:
    $readColumn = ($_POST['read'] == 'Good' || $_POST['read'] == 'Limited') ? '✔' : '';
    $writeColumn = ($_POST['write'] == 'Good' || $_POST['write'] == 'Limited') ? '✔' : '';
    $speakColumn = ($_POST['speak'] == 'Good' || $_POST['speak'] == 'Limited') ? '✔' : '';

    $sheet12->setCellValue('C2', $readColumn);
    $sheet12->setCellValue('D2', $writeColumn);
    $sheet12->setCellValue('E2', $speakColumn);

// Simpan Excel ke dalam folder
    $fileName = $folderName . '/Data_Registrasi.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($fileName);

    echo "Registrasi berhasil! Data telah disimpan ke $fileName.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="shortcut icon" href="images/logodaun.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">   
    
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: #198754;">
  <div class="container-fluid">
    <a class="navbar-brand text-white">Form Registrasi</a>
    <form action="index.php" method="get" class="d-flex">
      <button class="btn btn-outline-danger" type="submit">Back</button>
    </form>
  </div>
</nav>

<div class="container mt-5">
    <form method="POST" enctype="multipart/form-data" action="formRegis.php">
       

        <!-- Bagian KTP Passport -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian KTP Passport</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="action_type" class="form-label">Action Type*:</label>
                    <input type="text" name="action_type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pa" class="form-label">PA:</label>
                    <input type="text" name="pa"class="form-control">
                </div>
                <div class="mb-3">
                    <label for="personnel_area" class="form-label">Personnel Area:</label>
                    <input type="text" name="personnel_area" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="organization" class="form-label">Organization:</label>
                    <input type="text" name="organization" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employee_group" class="form-label">Employee Group:</label>
                    <input type="text" name="employee_group" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Position:</label>
                    <input type="text" name="position" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="employment_status" class="form-label">Employment Status:</label>
                    <input type="text" name="employment_status" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="gender_key" class="form-label">Gender Key*:</label>
                    <input type="text" name="gender_key" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="religion" class="form-label">Religion*:</label>
                    <input type="text" name="religion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="birthplace" class="form-label">BirthPlace*:</label>
                    <input type="text" name="birthplace" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nationality" class="form-label">Nationality*:</label>
                    <input type="text" name="nationality" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="martial_status" class="form-label">Martial Status Key*:</label>
                    <input type="text" name="martial_status" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ms_since" class="form-label">MS Since:</label>
                    <input type="text" name="ms_since" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="ethnic_origin" class="form-label">Ethnic Origin*:</label>
                    <input type="text" name="ethnic_origin" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Birth Date*:</label>
                    <input type="date" name="birth_date" class="form-control" required>
                </div>
            </div>
            </div>

        <!-- Bagian Personal Data -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Personal Data</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="no" class="form-label">No:</label>
                    <input type="text" name="no" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="pers_no" class="form-label">Pers.No:</label>
                    <input type="text" name="pers_no" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="name_personal" class="form-label">Name*:</label>
                    <input type="text" name="name_personal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pt" class="form-label">PT:</label>
                    <input type="text" name="pt" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="id_type" class="form-label">Type of Identification:</label>
                    <input type="text" name="id_type" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="id_number" class="form-label">ID Number*:</label>
                    <input type="text" name="id_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="issue_date" class="form-label">Issue Date*:</label>
                    <input type="date" name="issue_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="id_exp_date" class="form-label">ID ExpDate*:</label>
                    <input type="date" name="id_exp_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="place_issue" class="form-label">Place Issue*:</label>
                    <input type="text" name="place_issue" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="country_of_issue" class="form-label">Country of Issue:</label>
                    <input type="text" name="country_of_issue" class="form-control">
                </div>
            </div>
            </div>

        <!-- Bagian Address -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Address</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="no" class="form-label">No:</label>
                    <input type="text" name="no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pers_no" class="form-label">Pers.No:</label>
                    <input type="text" name="pers_no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type*:</label>
                    <input type="text" name="type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address_record_type" class="form-label">Address Record Type:</label>
                    <input type="text" name="address_record_type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="street_and_house_number" class="form-label">Street and House Number*:</label>
                    <input type="text" name="street_and_house_number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City*:</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="postal_code" class="form-label">Postal Code*:</label>
                    <input type="text" name="postal_code" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="district" class="form-label">District*:</label>
                    <input type="text" name="district" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="telephone_no" class="form-label">Telephone No*:</label>
                    <input type="text" name="telephone_no" class="form-control" required>
                </div>
            </div>
            </div>

        <!-- Bagian BPJS -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian BPJS</h2>
            </div>
            <div class="card-body">
            <div class="mb-3">
                    <label for="name_personal" class="form-label">Name*:</label>
                    <input type="text" name="name_personal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pt" class="form-label">PT:</label>
                    <input type="text" name="pt" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bpjs_id" class="form-label">BPJS ID*:</label>
                    <input type="text" name="bpjs_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="number" class="form-label">Number:</label>
                    <input type="text" name="number" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="benefit_class_for_bpjs" class="form-label">Benefit Class For BPJS:</label>
                    <input type="text" name="benefit_class_for_bpjs" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bpjs_ketenagakerjaan" class="form-label">BPJS Ketenagakerjaan*:</label>
                    <input type="text" name="bpjs_ketenagakerjaan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="bpjs_lama" class="form-label">BPJS Lama:</label>
                    <input type="text" name="bpjs_lama" class="form-control">
                </div>
            </div>
            </div>

        <!-- Bagian NPWP -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian NPWP</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="pt" class="form-label">PT:</label>
                    <input type="text" name="pt" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="tax_2025" class="form-label">TAX:</label>
                    <input type="text" name="tax_2025" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tax_id" class="form-label">TAX ID*:</label>
                    <input type="text" name="tax_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date:</label>
                    <input type="text" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="td" class="form-label">TD:</label>
                    <input type="text" name="td" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="marital_status_of_the_employee" class="form-label">Martial Status of the Employee*:</label>
                    <input type="text" name="marital_status_of_the_employee" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="is_employee_entitled_to_spouse" class="form-label">Is Employee Entitled to Spouse:</label>
                    <input type="text" name="is_employee_entitled_to_spouse" class="form-control">
                </div>
            </div>
            </div>
        <!-- Bagian Family Details -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Family Details</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="employee_name" class="form-label">Name of Employee:</label>
                    <input type="text" name="employee_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="memb" class="form-label">Memb*:</label>
                    <input type="text" name="memb" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="family_name" class="form-label">Family Name*:</label>
                    <input type="text" name="family_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="birth_place" class="form-label">Birth Place:</label>
                    <input type="text" name="birth_place" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="religion" class="form-label">Religion:</label>
                    <input type="text" name="religion" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="nationality" class="form-label">Nationality:</label>
                    <input type="text" name="nationality" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="gender_key" class="form-label">Gender Key*:</label>
                    <input type="text" name="gender_key" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Birth Date*:</label>
                    <input type="date" name="birth_date" class="form-control" required>
                </div>
            </div>
            </div>
        <!-- Bagian Education -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Education</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name_personal" class="form-label">Name:</label>
                    <input type="text" name="name_personal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration*:</label>
                    <input type="text" name="duration" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="educational_establishment" class="form-label">Educational Establishment*:</label>
                    <input type="text" name="educational_establishment" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="institute_location" class="form-label">Institute/Location*:</label>
                    <input type="text" name="institute_location" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="certificate" class="form-label">Certificate*:</label>
                    <input type="text" name="certificate" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="branch_of_study" class="form-label">Branch of Study*:</label>
                    <input type="text" name="branch_of_study" class="form-control" required>
                </div>
            </div>
            </div>
        <!-- Bagian Int -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Int Working</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name_personal" class="form-label">Name:</label>
                    <input type="text" name="name_personal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="company" class="form-label">Company:</label>
                    <input type="text" name="company" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="city_id" class="form-label">City_id:</label>
                    <input type="text" name="city_id" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="position_held" class="form-label">Position Held:</label>
                    <input type="text" name="position_held" class="form-control">
                </div>
            </div>
            </div>
        <!-- Bagian Ex -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Bagian Ex Working</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name_personal" class="form-label">Name:</label>
                    <input type="text" name="name_personal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date*:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date*:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="company" class="form-label">Company:</label>
                    <input type="text" name="company" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="city_id" class="form-label">City_id:</label>
                    <input type="text" name="city_id" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="position_held" class="form-label">Position Held:</label>
                    <input type="text" name="position_held" class="form-control">
                </div>
            </div>
            </div>
        <!-- Bagian Bank Detail -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Bank Detail</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name_personal" class="form-label">Name:</label>
                        <input type="text" name="name_personal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="pt" class="form-label">PT:</label>
                        <input type="text" name="pt" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="bank_country" class="form-label">Bank Country:</label>
                        <input type="text" name="bank_country" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mwp_bank_name" class="form-label">MWP & HMP Bank Name:</label>
                        <input type="text" name="mwp_bank_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mwp_bank_key_branch_address" class="form-label">MWP & HMP Bank Key/Branch/Address:</label>
                        <input type="text" name="mwp_bank_key_branch_address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mwp_account_number" class="form-label">MWP & HMP Account Number:</label>
                        <input type="text" name="mwp_account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="wia_bank_name" class="form-label">WIA Bank Name:</label>
                        <input type="text" name="wia_bank_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="wia_bank_key_branch_address" class="form-label">WIA Bank Key/Branch/Address:</label>
                        <input type="text" name="wia_bank_key_branch_address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="wia_account_number" class="form-label">WIA Account Number:</label>
                        <input type="text" name="wia_account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method:</label>
                        <input type="text" name="payment_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="payment_currency" class="form-label">Payment Currency:</label>
                        <input type="text" name="payment_currency" class="form-control">
                    </div>
                </div>
            </div>

        <!-- Bagian Training -->

            <div class="card mb-4">
                <div class="card-header">
                    <h2>Training</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name_personal" class="form-label">Name*:</label>
                        <input type="text" name="name_personal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="bank_country" class="form-label">Bank Country*:</label>
                        <input type="text" name="bank_country" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_training" class="form-label">Jenis Training*:</label>
                        <input type="text" name="jenis_training" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="instansi_penyelenggara" class="form-label">Instansi/Penyelenggara*:</label>
                        <input type="text" name="instansi_penyelenggara" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="instruktur_pembicara" class="form-label">Instruktur/Pembicara*:</label>
                        <input type="text" name="instruktur_pembicara" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="kota_negara" class="form-label">Kota/Negara*:</label>
                        <input type="text" name="kota_negara" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_periode" class="form-label">Tahun Periode*:</label>
                        <input type="text" name="tahun_periode" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- Bagian Language -->
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Language</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="language" class="form-label">Language:</label>
                        <input type="text" name="language" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="read" class="form-label">Read {Unable, Limited, Good}:</label>
                        <select name="read" class="form-control">
                            <option value="Unable">Unable</option>
                            <option value="Limited">Limited</option>
                            <option value="Good">Good</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="write" class="form-label">Write {Unable, Limited, Good}:</label>
                        <select name="write" class="form-control">
                            <option value="Unable">Unable</option>
                            <option value="Limited">Limited</option>
                            <option value="Good">Good</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="speak" class="form-label">Speak {Unable, Limited, Good}:</label>
                        <select name="speak" class="form-control">
                            <option value="Unable">Unable</option>
                            <option value="Limited">Limited</option>
                            <option value="Good">Good</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="computer_literacy" class="form-label">Komputer Literacy {Limited, Good, Very Good}:</label>
                        <select name="computer_literacy" class="form-control">
                            <option value="Limited">Limited</option>
                            <option value="Good">Good</option>
                            <option value="Very Good">Very Good</option>
                        </select>
                    </div>
                </div>
            </div>

        <!-- Pengumpulan Berkas -->
            <div class="card mb-4">
            <div class="card-header">
                <h2>Pengumpulan Berkas</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="surat_lamaran" class="form-label">Surat Lamaran*:</label>
                    <input type="file" name="surat_lamaran" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="cv" class="form-label">CV*:</label>
                    <input type="file" name="cv" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kk" class="form-label">Kartu Keluarga*:</label>
                    <input type="file" name="kk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ktp" class="form-label">KTP*:</label>
                    <input type="file" name="ktp" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pas_foto" class="form-label">Pas Foto*:</label>
                    <input type="file" name="pas_foto" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="ijazah" class="form-label">Ijazah*:</label>
                    <input type="file" name="ijazah" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="supporting_doc" class="form-label">Dokumen Pendukung:</label>
                    <input type="file" name="supporting_doc" class="form-control">
                </div>
            </div>
            </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<!-- Link ke JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
