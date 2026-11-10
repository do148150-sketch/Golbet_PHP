<!DOCTYPE html>
<html lang="es">

@include('layouts.head')

<body>

    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <span class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block fs-3 fw-bold">⚽ GolBet</span>
                                </span>
                            </div>

                            @yield('content')

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    @include('layouts.scripts')

    @stack('scripts')
</body>

</html>
