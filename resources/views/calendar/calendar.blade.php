<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>郑州大学校历</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,minimal-ui" />
    <link rel="stylesheet" type="text/css" href="http://7xpuml.com1.z0.glb.clouddn.com/calendar.css" />

    <style>
        .act {
            margin-top: 85px;
            margin-bottom: 20px;
            text-align: center;
        }

        .act button {
            margin-right: 30px;
        }
    </style>
</head>

<body>

<div class="head">
    <h1></h1>
</div>

<div class="wrap">
    <ul class="week-f">
        <li>一</li>
        <li>二</li>
        <li>三</li>
        <li>四</li>
        <li>五</li>
        <li class="wk">六</li>
        <li class="wk">日</li>
    </ul>

    <div id="calendar"></div>
</div>

<script src="http://7xpuml.com1.z0.glb.clouddn.com/jqmobi.js"></script>
<script src="http://7xpuml.com1.z0.glb.clouddn.com/calendar.js"></script>

<script>
    (function() {
        calendarIns = new calendar.calendar( {
            count: 8,
            selectDate: new Date(),
            selectDateName: '',
            minDate: new Date(),
            maxDate: new Date('2017-02-28'),
            isShowHoliday: true,
            isShowWeek: false
        } );

        var date = Math.ceil(Math.round(new Date('2016-09-15')-new Date())/1000/60/60/24);
        $(".head h1").text('距离中秋还有'+date+'天');
    })();
</script>

</body>
</html>
