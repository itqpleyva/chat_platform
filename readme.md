<h1>TellMe is a Chat platform that allows direct and real-time communication of users logged into the system</h1>

<h2>It has as fundamental functionalities</h2>
<ul>
    <li>User logging on the platform</li>
    <li>Sending messages to connected users</li>
    <li>Queuing of sent messages until the target user reads them</li>
    <li>Real-time notifications about message receipt</li>
    <li>Real-time update of connected users</li>
    <li>Message history display settings and the time the message was received</li>
</ul>

to install:
<ul>
  <li>1-composer install</li>
 <li>2-npm install</li>
</ul> 
after installed all npm and composer dependencies, run:
<ul>
  <li>1-php artisan serve //to run the project</li>
 <li>2-php artisan websockets:serve //to run the websockets serve</li>
</ul> 

It uses the following technologies as a base:

in package.json:
<ul>
    <li>"laravel-echo": "^1.6.1",</li>
    <li>"moment": "^2.24.0",</li>
    <li>"pusher-js": "^5.0.2"</li>
    <li>"axios": "^0.19",</li>
    <li>"bootstrap": "^4.0.0",</li>
    <li>"cross-env": "^5.1",</li>
    <li>"jquery": "^3.2",</li>
    <li>"laravel-mix": "^4.0.7",</li>
    <li>"lodash": "^4.17.13",</li>
    <li>"popper.js": "^1.12",</li>
    <li>"resolve-url-loader": "^2.3.1",</li>
    <li>"resolve-url-loader": "^2.3.1",</li>
    <li>"sass": "^1.20.1",</li>
    <li>"sass-loader": "7.*",</li>
    <li>"vue": "^2.5.17",</li>
    <li>"vue-template-compiler": "^2.6.10"</li>   
</ul>
in composer.json:
<ul>
    <li>"beyondcode/laravel-websockets": "^1.3",</li>
    <li>"fideloper/proxy": "^4.0",</li>
    <li>"laravel/framework": "^6.2",</li>
    <li>"laravel/tinker": "^1.0",</li>
    <li>"pusher/pusher-php-server": "~3.0"</li>   
</ul>


