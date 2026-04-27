<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NodePlan - Bienvenido</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Henny+Penny&family=Pacifico&family=Playpen+Sans:wght@100..800&family=Playwrite+IE&family=Quintessential&family=Sofia&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&family=Xanh+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
</head>
<body class="bg-fondo text-gray-800 font-gummy">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <img src="/imagenes/LogoNodePlan.jpg" alt="Logo" class="h-12 w-auto rounded-full object-cover"><span class="ml-1 text-[10px] bg-amber-400 text-white px-1.5 py-0.5 rounded-full uppercase tracking-wider font-bold">
    Beta
</span><span class="text-xl font-bold text-verdes-dark">NodePlan</span>
                </div>
                
                <div class="hidden md:flex space-x-8 font-indigo-medium">
                    <a href="#inicio" class="hover:text-verdes-dark transition">Inicio</a>
                    <a href="#about" class="hover:text-verdes-dark transition">Sobre nosotros</a>
                    <a href="#servicios" class="hover:text-verdes-dark transition">Servicios</a>
                    <a href="#Saber" class="bg-verdes text-verdes-dark px-5 py-2 rounded-full shadow-sm shadow-verdes-dark hover:bg-verdes-light transition">Download</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/calendario') }}" class="bg-verdes text-verdes-dark px-5 py-2 rounded-full shadow-sm shadow-verdes-dark hover:bg-verdes-light transition">Ir al Calendario</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600  hover:text-marron font-medium">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-verdes text-verdes-dark px-5 py-2 rounded-full  shadow-sm shadow-verdes-dark hover:bg-verdes-light transition">¡Regístrate!</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    
        
    <section id="inicio" class="relative hero-background py-32 md:py-48 font-gummy">
        <div class="absolute inset-0 bg-black opacity-20 z-0 rounded-b-[40px] md:rounded-b-[80px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-white">
            <div class="flex flex-col items-center text-center gap-12">
                
                <div class="w-full">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                        Organiza tu <span class="text-verdes">éxito académico</span> con NodePlan
                    </h1>
                    <p class="text-xl md:text-2xl text-verdes-light max-w-3xl mx-auto mb-10 leading-relaxed">
                        Gestiona tu tiempo como un profesional con nuestra herramienta intuitiva. Faltan cosas por llegar, pero ¡poco a poco iremos ampliando!
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('register') }}" class="bg-verdes text-verdes-dark px-10 py-4 rounded-full font-bold shadow-sm shadow-verdes-dark hover:bg-verdes-light transition duration-300 w-full sm:w-auto text-center">
                            Empezar Gratis
                        </a>
                        <a href="#about" class="bg-transparent border-2 border-white text-white px-10 py-4 rounded-full font-bold   transition duration-300 w-full sm:w-auto text-center">
                            Conoce Nuestra Historia
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="about" class="py-20 bg-fondo">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-playwrite-bold mb-6">Nuestra Historia</h2>
            <p class="text-xl text-gray-600 leading-relaxed">
                Lo que hoy ves como NodePlan nació de una chispa de curiosidad en las aulas. Lo que comenzó como un simple proyecto de estudios, impulsado por las ganas de aprobar y aprender, se transformó rápidamente en algo mucho más grande. Al ver el potencial de lo que estábamos creando, decidimos no ponerle límites y seguir ampliándolo hasta convertirlo en la plataforma que es hoy. Todavía queda mucho por hacer
                <br>
                En un mundo donde la tecnología avanza a pasos agigantados, sabemos que la organización es la clave del éxito académico. Por eso, nuestra misión es facilitar a los estudiantes la gestión de sus tareas y la recogida de temario en un solo lugar. Creemos firmemente que, con las herramientas adecuadas, cualquier estudiante puede alcanzar su máximo potencial
            </p>
            <div class="mt-8 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-rocket text-amber-600 text-xl"></i> <h3 class="text-amber-800 font-bold text-lg">Estamos en fase Beta</h3>
                </div>
                <p class="mt-2 text-amber-700 italic leading-snug">
                    Sabemos que todavía faltan muchas funciones por implementar, ¡pero esto es solo el comienzo! NodePlan se irá ampliando poco a poco con actualizaciones constantes para ofrecerte la mejor herramienta de estudio posible.
                </p>
            </div>
        </div>
    </section>

    <section id="servicios" class="py-20 bg-fondo">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-playwrite-bold mb-12 italic">¿Qué puedes hacer con nuestra App?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm shadow-marron hover:shadow-md transition border border-gray-100">
                    <div class="w-12 h-12 bg-verdes-dark text-verdes-light rounded-lg flex items-center justify-center mb-6 mx-auto">
                        <i class="fa-solid fa-calendar-days text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Calendario Dinámico</h3>
                    <p class="text-gray-600">Visualiza tus eventos por día, semana o mes con una interfaz intuitiva y rápida.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm shadow-marron hover:shadow-md transition border border-gray-100">
                    <div class="w-12 h-12 bg-verdes text-verdes-dark rounded-lg flex items-center justify-center mb-6 mx-auto">
                        <i class="fa-solid fa-bell text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Notificaciones</h3>
                    <p class="text-gray-600">Recibe recordatorios en tiempo real para que nunca olvides una cita importante.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm shadow-marron hover:shadow-md transition border border-gray-100">
                    <div class="w-12 h-12 bg-verdes-light text-verdes rounded-lg flex items-center justify-center mb-6 mx-auto">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Colaboración</h3>
                    <p class="text-gray-600">Comparte tus calendarios con amigos o colegas de trabajo de forma segura.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white py-10 border-t">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} MiApp Project. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>