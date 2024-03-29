<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>简单Marker</title>
</head>
<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77"></script>
<style type="text/css">
    html,
    body {
        height: 100%;
        margin: 0px;
        padding: 0px;
    }

    #container {
        width: 66%;
        height: 100%;
        margin: 0 auto;
    }
</style>

<body>
<div id="container"></div>
<script>
    var center = new TMap.LatLng({{$coordinate['lat']}}, {{$coordinate['lng']}});//设置中心点坐标
    //初始化地图
    var map = new TMap.Map("container", {
        center: center
    });

    //初始化marker
    var marker = new TMap.MultiMarker({
        id: "marker-layer", //图层id
        map: map,
        styles: { //点标注的相关样式
            "marker": new TMap.MarkerStyle({
                "width": 25,
                "height": 35,
                "anchor": { x: 16, y: 32 },
                "src": "https://mapapi.qq.com/web/lbs/javascriptGL/demo/img/markerDefault.png"
            })
        },
        geometries: [{ //点标注数据数组
            "id": "demo",
            "styleId": "marker",
            "position": new TMap.LatLng({{$coordinate['lat']}}, {{$coordinate['lng']}}),
            "properties": {
                "title": "marker"
            }
        }]
    });



</script>
</body>

</html>
