<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>game generator</title>
    <style>
        .input-wrapper {
            margin: 50px
        }
        .input {
            padding: 10px;
            font-size: 15px;
            border-radius: 20px;
            border-color: #f22135;
            outline: none;
            width: 80%;
            display: flex;
            flex-direction: column;
        }
        .input-label {
            font-size: 20px;
            color: #f76a3f;
        }
        button {
            padding: 10px;
            border-radius: 10px;
            border-color: #f22135;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>pytania do prawdy</h1>
        </div>
        <div class="content">
            <div class="input-wrapper">
                <div class="input-label">dla meżczyzn</div>
                <input type="text" id="men-question" class="input" placeholder="wpisz pytanie">
                <button id="men-question-button">generuj</button>
            </div>
            <div class="input-wrapper">
                <div class="input-label">dla kobiet</div>
                <input type="text" id="women-question" class="input" placeholder="wpisz pytanie">
                <button id="woman-question-button">generuj</button>                
            </div>
            <div class="input-wrapper">
                <div class="input-label">uni sex</div>
                <input type="text" id="uni-sex" class="input" placeholder="wpisz pytanie">
                <button id="uni-sex-button">generuj</button>                
            </div>
        </div>
        <div class="objects">

        </div>
    </div>

    <script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>

    <!-- Załaduj nasz komponent reactowy. -->
    <script>
        'use strict';

        const e = React.createElement;

        class LikeButton extends React.Component {
            constructor(props) {
                super(props);
                this.state = { liked: false };
            }

            render() {
                if (this.state.liked) {
                    return 'You liked this.';
                }

                return e(
                'button',
                    { onClick: () => this.setState({ liked: true }) },
                'Like'
                );
            }
        }

        const domContainer = document.querySelector('#like_button_container');
        ReactDOM.render(e(LikeButton), domContainer);

    </script>

</body>
</html>