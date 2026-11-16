<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Autentikasi Staf | Coffee Senja</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <main class="auth-page">

        {{--PANEL KIRI: BRANDING COFFEE SENJA--}}

        <section class="brand-panel">

            {{-- Dekorasi biji kopi --}}
            <img
                src="{{ asset('asset/aset-bean.svg') }}"
                class="decoration bean bean-one"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-bean.svg') }}"
                class="decoration bean bean-two"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-bean.svg') }}"
                class="decoration bean bean-three"
                alt=""
                aria-hidden="true"
            >

            {{-- Dekorasi makanan dan minuman --}}
            <img
                src="{{ asset('asset/aset-croissant.svg') }}"
                class="decoration croissant"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-cookie.svg') }}"
                class="decoration cookie"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-cup.svg') }}"
                class="decoration cup"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-iced.svg') }}"
                class="decoration iced"
                alt=""
                aria-hidden="true"
            >

            <img
                src="{{ asset('asset/aset-toast.svg') }}"
                class="decoration toast"
                alt=""
                aria-hidden="true"
            >

            {{-- Logo Coffee Senja --}}
            <div class="brand-card">

                <img
                    src="{{ asset('asset/logo-coffe-senja-transparan.png') }}"
                    alt="Coffee Senja Logo"
                >

            </div>

        </section>


        {{--PANEL KANAN: FORM AUTENTIKASI--}}

        <section class="form-panel">

            <div class="auth-container">

                {{-- Heading --}}
                <div class="auth-heading">

                    <span class="brand-subtitle">
                        COFFEE SENJA
                    </span>

                    <h1>Masuk sebagai staf</h1>

                </div>


                {{-- Pesan kesalahan --}}
                @if ($errors->any())

                    <div class="alert-error" role="alert">

                        <span class="alert-icon">!</span>

                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>

                    </div>

                @endif


                {{-- Form autentikasi --}}
                <form
                    action="{{ route('login.process') }}"
                    method="POST"
                    class="auth-form"
                >

                    @csrf

                    {{-- Username --}}
                    <div class="form-group">

                        <label for="username">
                            Username
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                            autofocus
                        >

                    </div>


                    {{-- Password --}}
                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                    </div>


                    {{-- Tombol masuk --}}
                    <button type="submit" class="btn-submit">

                        <span>Masuk</span>

                        <span class="button-arrow">→</span>

                    </button>

                </form>


                {{-- Footer --}}
                <footer class="auth-footer">

                    <span>
                        &copy; {{ date('Y') }} Coffee Senja
                    </span>

                    <span>
                        Internal Staff Access
                    </span>

                </footer>

            </div>

        </section>

    </main>

</body>

</html>