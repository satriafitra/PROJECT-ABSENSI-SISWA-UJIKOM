@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    :root {
        --premium-gradient: linear-gradient(135deg, #FF8C00 0%, #FF4500 100%);
        --glass-white: rgba(255, 255, 255, 0.9);
    }

    .bg-hall-fame {
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    /* Podium Styling */
    .podium-container {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 20px;
        padding-top: 60px;
    }

    .podium-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        transition: 0.4s;
        position: relative;
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* Juara 1 (Tengah) */
    .podium-1 {
        width: 280px;
        z-index: 3;
        transform: translateY(-20px);
        border: 2px solid #FF8C00;
    }

    /* Juara 2 & 3 */
    .podium-side {
        width: 240px;
        z-index: 2;
    }

    .crown-wrapper {
        position: absolute;
        top: -45px;
        left: 50%;
        transform: translateX(-50%);
        filter: drop-shadow(0 5px 10px rgba(255, 215, 0, 0.4));
    }

    .avatar-podium {
        width: 90px;
        height: 90px;
        border-radius: 20px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
    }

    .text-orange-gradient {
        background: var(--premium-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    /* List Table Styling */
    .table-premium {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .table-premium tr {
        transition: 0.3s;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-premium tr:hover {
        background: #fffaf5;
        transform: scale(1.005);
    }
</style>

<div class="bg-hall-fame pb-5 animate__animated animate__fadeIn">
    <div class="container text-center pt-5 mb-5">
        <h1 class="display-4 font-weight-bold text-dark animate__animated animate__slideInDown">
            🏆 <span class="text-orange-gradient">Hall of Fame</span>
        </h1>
        <p class="text-muted lead">Apresiasi khusus untuk siswa dengan integritas tertinggi</p>
    </div>

    <div class="container">
        <div class="podium-container mb-5">
            @php $top3 = $leaderboard->take(3); @endphp

            @if(isset($top3[1]))
            <div class="podium-card podium-side animate__animated animate__fadeInLeft" style="animation-delay: 0.4s;">
                <div class="avatar-podium bg-light text-secondary">
                    {{ substr($top3[1]->student->name, 0, 1) }}
                </div>
                <h5 class="font-weight-bold mb-1">{{ $top3[1]->student->name }}</h5>
                <span class="badge badge-secondary mb-2">Rank #2</span>
                <h3 class="text-orange-gradient mb-0">{{ number_format($top3[1]->total_points) }}</h3>
                <small class="text-muted font-weight-bold">POINTS</small>
            </div>
            @endif

            @if(isset($top3[0]))
            <div class="podium-card podium-1 animate__animated animate__fadeInUp">
                <div class="crown-wrapper animate__animated animate__bounce animate__infinite animate__slow">
                    <i data-lucide="crown" size="48" fill="#FFD700" color="#FFD700"></i>
                </div>
                <div class="avatar-podium shadow-sm" style="background: #fff3e0; color: #FF8C00;">
                    {{ substr($top3[0]->student->name, 0, 1) }}
                </div>
                <h4 class="font-weight-bold mb-1 text-dark">{{ $top3[0]->student->name }}</h4>
                <span class="badge bg-premium-orange text-white mb-3 px-3 py-2">Grand Champion</span>
                <h2 class="text-orange-gradient mb-0 font-weight-black">{{ number_format($top3[0]->total_points) }}</h2>
                <small class="text-muted font-weight-bold">POINTS</small>
            </div>
            @endif

            @if(isset($top3[2]))
            <div class="podium-card podium-side animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
                <div class="avatar-podium bg-light text-secondary">
                    {{ substr($top3[2]->student->name, 0, 1) }}
                </div>
                <h5 class="font-weight-bold mb-1">{{ $top3[2]->student->name }}</h5>
                <span class="badge badge-bronze mb-2" style="background: #CD7F32; color: white;">Rank #3</span>
                <h3 class="text-orange-gradient mb-0">{{ number_format($top3[2]->total_points) }}</h3>
                <small class="text-muted font-weight-bold">POINTS</small>
            </div>
            @endif
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="table-premium p-2 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small uppercase">
                            <tr>
                                <th class="pl-4">RANK</th>
                                <th>STUDENT NAME</th>
                                <th class="text-right pr-4">TOTAL SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard->skip(3) as $index => $data)
                            <tr>
                                <td class="pl-4 font-weight-bold text-muted">#{{ $index + 4 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 bg-light rounded-lg d-flex align-items-center justify-content-center font-weight-bold" style="width: 40px; height: 40px; color: #FF8C00;">
                                            {{ substr($data->student->name, 0, 1) }}
                                        </div>
                                        <span class="font-weight-bold text-dark">{{ $data->student->name }}</span>
                                    </div>
                                </td>
                                <td class="text-right pr-4">
                                    <span class="badge badge-pill bg-light text-orange font-weight-bold px-3 py-2">
                                        {{ number_format($data->total_points) }} PTS
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection