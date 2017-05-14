<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Artefact</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script src="js/three.min.js"></script>
        <script src="js/OBJLoader.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #000;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .button{
                background-color: #fff;
                padding: 10px;
                height: 15hv;
                weight: 10hv;
                color: #000;
                font-family: pt-sans;
                text-decoration: none;
                border-radius: 5px;
            }

            .button .a{
                text-decoration: none;
               
            }

        </style>
       
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/admin') }}">Войти в панель администратора</a>
                    @else
                        <a href="{{ url('/admin') }}">Авторизация</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                     <div class="copy animated fadeIn">
                        <?php $autorization_caption = Voyager::setting('autorization_caption', ''); ?>
                        <img class="hidden-xs animated fadeIn" src="{{ Voyager::image($autorization_caption) }}">
                    </div>
                </div>
            <div class="button">
                <a class="button a" href="#">ПЕРЕЙТИ В КАТАЛОГ</a>
            </div>
            
            </div>
        </div>
        <script>
            
			var container;
			var camera, scene, renderer;
			var mouseX = 0, mouseY = 0;
			var windowHalfX = window.innerWidth/5;
			var windowHalfY = window.innerHeight/5;
			init();
			animate();
			function init() {
				container = document.createElement( 'div' );
				document.body.appendChild( container );
				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
				camera.position.z = 10;
				// scene
				scene = new THREE.Scene();
				var ambient = new THREE.AmbientLight( 0x101030 );
				scene.add( ambient );
				var directionalLight = new THREE.DirectionalLight( 0xffeedd );
				directionalLight.position.set( 0, 0, 1 );
				scene.add( directionalLight );
				// texture
				var manager = new THREE.LoadingManager();
				manager.onProgress = function ( item, loaded, total ) {
					console.log( item, loaded, total );
				};
				var texture = new THREE.Texture();
				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
						console.log( Math.round(percentComplete, 2) + '% downloaded' );
					}
				};
				var onError = function ( xhr ) {
				};
			//	var loader = new THREE.ImageLoader( manager );
			//	loader.load( 'textures/UV_Grid_Sm.jpg', function ( image ) {
			//		texture.image = image;
			//		texture.needsUpdate = true;
			//	} );
				// model
				var loader = new THREE.OBJLoader( manager );
				loader.load( 'models/test/vase.obj', function ( object ) {
					object.traverse( function ( child ) {
						if ( child instanceof THREE.Mesh ) {
							child.material.map = texture;
						}
					} );
					object.position.y = 0;
                    object.position.z = 10;
                    object.position.x = 0;
					scene.add( object );
				}, onProgress, onError );
				//
				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );
				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				//
				window.addEventListener( 'resize', onWindowResize, false );
			}
			function onWindowResize() {
				windowHalfX = window.innerWidth / 2;
				windowHalfY = window.innerHeight / 2;
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
				renderer.setSize( window.innerWidth, window.innerHeight );
			}
			function onDocumentMouseMove( event ) {
				mouseX = ( event.clientX - windowHalfX ) /10;
				mouseY = ( event.clientY - windowHalfY )/10;
			}
			//
			function animate() {
				requestAnimationFrame( animate );
				render();
			}
			function render() {
				camera.position.x += ( mouseX - camera.position.x ) * 0.1;
				camera.position.y += ( -mouseY - camera.position.y ) * 0.1;
				camera.lookAt( scene.position );
				renderer.render( scene, camera );
			}


         </script>   

    </body>
 
</html>
