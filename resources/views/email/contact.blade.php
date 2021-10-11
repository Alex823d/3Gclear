<!DOCTYPE html>
<html>
<head>
    <title>{{__('Uproject')}}</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <style>
        *{ margin:0; padding:0}
        html, body{width:100%; min-height:100%;}
        #wrapper{
            width:92%;
            max-width:600px;
            padding:29px 4% 10px 4%;
            background:#d90a2c no-repeat right bottom;
            background-size:auto 100%;
            margin:50px auto;
        }
        #mainContent{
            -webkit-box-shadow: #d90a2c;
            -moz-box-shadow: #d90a2c;
            box-shadow: #d90a2c;
            background-color:#fff;
            margin-bottom:13px;
            position:relative;
            clear:both;
            overflow:hidden;
            border-bottom-right-radius:40px;
        }
        #head h1{
            width:94px;
            height:47px;
            margin:25px 5.45%;
        }
        #content{
            width:92%;
            max-width:550px;
            margin:25px auto;
        }
        #content h2{
            color: #d90a2c;
            font-size:15px;
            margin:0 5.45% 25px 5.45%;
        }
        #content h5{
            color: #d90a2c;
            font-size:12px;
            margin:0 5.45% 25px 5.45%;
        }
    </style>
</head>
<body>
<section id="wrapper">
    <section id="mainContent">
        <section id="content">
            <h2> {{__('სახელი')}}: {{$data['first_name']}}</h2>
            <h2> {{__('გვარი')}}: {{$data['last_name']}}</h2>
            <h2> {{__('ტელეფონი')}}: {{$data['phone']}}</h2>
            <h2> {{__('ელ-ფოსტა')}}: {{$data['email']}}</h2>
            <h2> {{__('შეტყობინება')}}</h2>
            <h5>{{$data['message']}}</h5>
        </section>
    </section>
</section>
</body>
</html>
