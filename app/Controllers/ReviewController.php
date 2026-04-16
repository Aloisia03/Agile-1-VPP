<?php
namespace App\Controllers;

use App\Models\Review;

class ReviewController
{
    protected $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
    }

    public function index()
    {
        $reviews = $this->reviewModel->getAllReviews();
        return view('reviews.index', compact('reviews'));
    }

    public function updateStatus($id)
    {
        $status = $_POST['status'] ?? 'pending';
        $this->reviewModel->updateStatus($id, $status);
        header("Location: /Agile-1-VPP/reviews");
        exit;
    }
}