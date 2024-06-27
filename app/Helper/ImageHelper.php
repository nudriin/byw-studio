<?php
// require __DIR__ . "/../../public/css/style.css";
// require_once __DIR__ . "/../../public/uploaded_"
function getImage(string $imageName) : string
{
    // Sesuaikan ini dengan cara Anda menyimpan gambar
    $imageDirectory = 'http://localhost/images/'; // Ganti dengan path yang benar
    // $imageDirectory = 'http://1291-114-10-143-235.ngrok-free.app/images/'; // Ganti dengan path yang benar
    $imagePath = $imageDirectory . $imageName;
    
    // Pastikan gambar ada sebelum mengembalikan URL
    // if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
    //     return $imagePath;
    // } else {
    //     // return 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'; // Ganti dengan path gambar placeholder
    // }
    return $imagePath;
}

function getUploadedImg(string $image) : string
{
    return "http://localhost/uploaded_image/" . $image;
}

function moveImage(string $tmp_name, string $image, string $dirPath)
{
    $path = __DIR__ . "/../../public/uploaded_image/$dirPath/" . $image;
    move_uploaded_file($tmp_name, $path);
}

function deleteImage(string $image, string $dirPath) : bool
{
    $imagePath =  __DIR__ . "/../../public/uploaded_image/$dirPath/" . $image;
    return unlink($imagePath);
    // return $succes;
}

function getStyle() : string
{
    return __DIR__ . "/../../public/css/style.css";
}