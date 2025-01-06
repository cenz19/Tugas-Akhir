@extends('layouts.app')

@section('title')
<p style="font-weight: 400; color:rgba(255,255,255,0.5);">Pages <span style="color:#fff">/ UU ITE</span></p>
<p>UU ITE</p>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <!-- Flaming Card -->
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <i class="fas fa-fire fa-3x" style="color: red;"></i> <!-- Flaming Icon -->
                        <h5 class="card-title mt-3">Flaming</h5>
                        <p class="card-text">
                            Pelanggaran flaming terjadi ketika seseorang secara sengaja menyebarkan kebencian atau pernyataan kasar yang dapat memicu pertikaian di dunia maya.
                        </p>
                        <p class="legal-info"><strong>Hukum:</strong> Pasal 27 ayat (3) UU ITE menyebutkan bahwa setiap orang yang dengan sengaja dan tanpa hak mendistribusikan informasi elektronik yang mengandung unsur kebencian dapat dijerat pidana. <br>
                        <strong>Hukuman:</strong> Pidana penjara paling lama 6 tahun atau denda maksimal Rp1.000.000.000,-</p>
                    </div>
                </div>
            </div>

            <!-- Harassment Card -->
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x" style="color: orange;"></i> <!-- Harassment Icon -->
                        <h5 class="card-title mt-3">Harassment</h5>
                        <p class="card-text">
                            Harassment melibatkan tindakan pelecehan atau ancaman yang dilakukan secara terus-menerus terhadap individu di media sosial.
                        </p>
                        <p class="legal-info"><strong>Hukum:</strong> Pasal 29 UU ITE mengatur tentang ancaman atau pelecehan secara elektronik, yang dilakukan untuk menakut-nakuti orang lain. <br>
                        <strong>Hukuman:</strong> Pidana penjara paling lama 6 tahun dan denda paling banyak Rp1.000.000.000,-</p>
                    </div>
                </div>
            </div>

            <!-- Denigration Card -->
            <div class="col-md-4">
                
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users-slash fa-3x" style="color: purple;"></i> <!-- Denigration Icon -->
                        <h5 class="card-title mt-3">Denigration</h5>
                        <p class="card-text">
                            Denigrasi adalah tindakan merendahkan atau mencemarkan nama baik seseorang melalui penyebaran informasi negatif yang tidak benar.
                        </p>
                        <p class="legal-info"><strong>Hukum:</strong> Pasal 27 ayat (3) UU ITE yang mengatur penyebaran informasi elektronik yang merugikan pihak lain. <br>
                        <strong>Hukuman:</strong> Pidana penjara paling lama 6 tahun atau denda paling banyak Rp1.000.000.000,-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<!-- Optional: Include FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
@endsection

<!-- CSS Styling -->
<style>
    .custom-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-card .card-body {
        padding: 20px;
    }

    .custom-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }

    .custom-btn:hover {
        background-color: #0056b3;
    }

    .legal-info {
        font-size: 14px;
        color: #555;
        margin-top: 10px;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .card-text {
        font-size: 15px;
        color: #444;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
    }

    .fas {
        margin-bottom: 10px;
    }
</style>
