@extends('fuzja-site.layouts.layout')

@section('content')
<div class="content">
    <p class="content-title">Aplikacja Dla Zakochanych!</p>
    <div style="font-size: 24px; text-align: center">Korzystając z aplikacji zyskujesz dostęp do:</div>                
    <ul>
        <li>
            <div class="screenshot-title">Pulpitu</div>
            <div class="list-item-content-wrapper">
                <img src="/fuzja-site-assets/home-screen.png" alt="home screen" class="screenshot" />
                <div class="screenshot-description">
                    Pulpit aplikacji pozwala na ustawianie własnego tła. Licznik na górze ekranu pokazuje jak długo jesteście w związku.
                    Dodatkowo na pulpicie można umieszczać widgety z ważnymi datami, dzięki czemu nigdy nie przegapisz spotkania czy rocznicy.
                </div>
            </div>
        </li>
        <li>
            <div class="screenshot-title">Czatu</div>
            <div class="list-item-content-wrapper">                        
                <img src="/fuzja-site-assets/chat-screen.png" alt="chat screen" class="screenshot" />
                <div class="screenshot-description">
                    Czat pozwala na komunikację tylko z jedną osobą - twoim partnerem. Wyposażony został w darmowe naklejki podzielone na kategorie, które lepiej pozwolą wyrazić to co chcesz powiedzieć.
                </div>
            </div>
        </li>
        <li>
            <div class="screenshot-title">Galerii</div>
            <div class="list-item-content-wrapper">                        
                <img src="/fuzja-site-assets/gallery-screen.png" alt="gallery screen" class="screenshot" />
                <div class="screenshot-description">
                    Galeria czyli Wasz wspólny album, dzięki któremu w każdej chwili macie dostęp do waszych zdjęć, bez konieczności ich przesyłania przez messenger.
                    Do każdego zdjęcia możecie dodawać opisy, które pomogą lepiej upamiętnić daną chwilę.
                </div>
            </div>
        </li>
        <li>
            <div class="screenshot-title">Gra w Prawda czy Wyzwanie</div>
            <div class="list-item-content-wrapper">
                <div style="display: flex; flex-direction: column">
                    <img src="/fuzja-site-assets/game-start-screen.png" alt="game screen" class="screenshot" />
                    <img src="/fuzja-site-assets/game-bottle-screen.png" alt="game screen" class="screenshot" />                        
                </div>
                <div class="screenshot-description">
                    Gra w Prawda czy Wyzwanie nazywana również grą w butelkę wyposażona została w treści podzielone na poziomy trudności: soft i hot.
                    Pozwolą nie tylko na świetną wieczorową rozrywkę, ale także na lepsze poznanie się lepiej nawzajem. 
                </div>
            </div>
        </li>
    </ul>
</div>
@include('fuzja-site.inc.footer')
@endsection