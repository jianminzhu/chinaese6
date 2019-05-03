var ParkMap = /** @class */ (function () {
    function ParkMap(sel, _a, spritejs) {
        if (sel === void 0) { sel = "#container"; }
        var _b = _a === void 0 ? { resolution: [3427, 1920], map: { bottom: bottom, up: up, down: down } } : _a, _c = _b.resolution, _d = _c[0], width = _d === void 0 ? 3427 : _d, _e = _c[1], heigth = _e === void 0 ? 1920 : _e, _f = _b.bg, bg = _f === void 0 ? "images/bg2.png" : _f, _g = _b.map, _h = _g.bottom, bottom = _h === void 0 ? "images/map_bottom.png?v=1" : _h, _j = _g.up, up = _j === void 0 ? "images/map_up.png?v=1" : _j, _k = _g.down, down = _k === void 0 ? "images/map_down.png?v=1" : _k;
        if (spritejs === void 0) { spritejs = window.spritejs; }
        this.spritejs = spritejs;
        this.cars = {};
        this.carAnimates = {};
        try {
            var _l = this.spritejs, Scene = _l.Scene, Sprite = _l.Sprite, Group = _l.Group;
            var scene = new Scene(sel, { resolution: [width, heigth] });
            var layBg = this.layerBg = scene.layer();
            layBg.append(new Sprite({ pos: [0, 0], textures: bg }));
            layBg.append(new Sprite({ pos: [371, 158], textures: bottom }));
            var gCars = this.groupCars = new Group({ pos: [371, 158] });
            layBg.append(new Sprite({ pos: [434, 147], textures: down }));
            layBg.append(gCars);
            layBg.append(new Sprite({ pos: [434, 147], textures: up }));
        }
        catch (e) {
        }
    }
    ParkMap.prototype.getCar = function (id) {
        return this.cars[id];
    };
    ParkMap.prototype.addCar = function (_a) {
        var id = _a.id, _b = _a.pos, x = _b[0], y = _b[1], _c = _a.size, _d = _c[0], width = _d === void 0 ? 80 : _d, _e = _c[1], height = _e === void 0 ? 80 : _e, textures = _a.textures;
        var Sprite = this.spritejs.Sprite;
        var car = new Sprite(textures);
        car.attr({
            anchor: [0, 0.3],
            size: [width, height],
            pos: [x, y],
        });
        this.cars[id] = car;
        car.animate(this.genCarImgs(textures), {
            duration: 2000,
            iterations: Infinity,
            direction: 'alternate',
        });
        this.groupCars.append(car);
        return car;
    };
    ParkMap.prototype.genCarImgs = function (img) {
        var prefix = img.replace(/\d{1,2}\.png$/, "");
        var arr = [];
        for (var i = 1; i <= 12; i++) {
            arr.push({ textures: "" + prefix + i + ".png" });
        }
        return arr;
    };
    ;
    ParkMap.prototype.move = function (id, _a, durationSecond, isDemo) {
        var x = _a[0], y = _a[1];
        if (durationSecond === void 0) { durationSecond = 2; }
        if (isDemo === void 0) { isDemo = false; }
        var car = this.cars[id];
        if (isDemo) {
            var paths = [
                { pos: car.attr().pos },
                { pos: [x, y] }
            ];
            car.animate(paths, {
                iterations: Infinity,
                direction: 'alternate',
                duration: durationSecond * 1000
            });
        }
        car.transition(durationSecond).attr({ pos: [x, y] });
    };
    ParkMap.prototype.movePoints = function (id, points, durationSecond) {
        if (durationSecond === void 0) { durationSecond = 2; }
        var car = this.cars[id];
        car.animate(points.map(function (v, i) {
            return { pos: v };
        }), {
            iterations: Infinity,
            direction: 'alternate',
            duration: durationSecond * 1000
        });
    };
    ParkMap.prototype.moveOffset = function (id, _a, durationSecond) {
        var x = _a[0], y = _a[1];
        if (durationSecond === void 0) { durationSecond = 2000; }
        var car = this.cars[id];
        var srcX = car.attr("x");
        var srcY = car.attr("y");
        this.carAnimates[id] = this.cars[id].transition(durationSecond).attr({ pos: [srcX + x, srcY + y] });
    };
    return ParkMap;
}());
;
if (typeof module === 'object') {
    window.jQuery = window.$ = module.exports;
}
$(function () {
    var id = "container";
    var sel = "#" + id;
    if ($(sel).length == 0) {
        $("body").prepend("<div id=\"" + id + "\" style=\"position:absolute;left: 0;top: 0px;width: 3427px;height:1920px;z-index: 10\"></div>");
    }
    var map = new ParkMap(sel);
    var duration = 20;
    map.addCar({ id: "car1", pos: [470, 203], size: [100, 100], textures: "images/cars/pink/1.png" });
    map.addCar({ id: "car4", pos: [470, 203], size: [100, 100], textures: "images/cars/violet/1.png" });
    map.movePoints("car1", [[470, 203], [770, 418], [240, 628], [515, 913], [515, 913]], duration * (1 + Math.random()));
    map.movePoints("car4", [[470, 203], [770, 418], [240, 628], [515, 913], [515, 913]].reverse(), duration * (1 + Math.random()));
    map.addCar({ id: "car2", pos: [810, 80], size: [100, 100], textures: "images/cars/blue/1.png" });
    map.addCar({ id: "car5", pos: [810, 80], size: [100, 100], textures: "images/cars/green/1.png" });
    map.movePoints("car2", [[1120, 260], [1120, 260], [790, 438], [1050, 638], [543, 916]].reverse(), duration * (1 + Math.random()));
    map.movePoints("car5", [[810, 80], [810, 80], [1120, 260], [790, 438], [1050, 638], [543, 916], [543, 916]], duration * (1 + Math.random()));
    map.addCar({ id: "car3", pos: [1170, 20], size: [100, 100], textures: "images/cars/green/1.png" });
    map.addCar({ id: "car6", pos: [1170, 20], size: [100, 100], textures: "images/cars/pink/1.png" });
    map.movePoints("car3", [[1170, 20], [1740, 250], [1740, 250], [1115, 615], [1390, 830], [1390, 830]], duration * (1 + Math.random()));
    map.movePoints("car6", [[1170, 20], [1170, 20], [1740, 250], [1740, 250], [1115, 615], [1390, 830]].reverse(), duration * (1 + Math.random()));
});
$(function () {
    let srcW = 3427;
    let srcH = 1920;
    let width = 3427;
    let resolution = [srcW, srcH]

    scaleSize = function (dataset, index, type, height, scala = [0, 250]) {
        let hourMaxIndex = d3.scan(dataset, function (a, b) {
            return b[type] - a[type]
        });
        let scale = d3.scaleLinear()
            .domain([0, dataset[hourMaxIndex][type]])
            .range(scala);
        return [scale(dataset[index][type]), height]
    }
    var now = moment()

    huanWeiData = Array(4).fill(0).map((v, i) => {
        let hour = 3 + Math.floor(9 * Math.random());
        return {hour: hour, area: Math.floor(hour * ((Math.random()) * 300))}
    })
    huanWei = huanWeiData.map(function (it, index) {
        var date = now.add(-1 * index, 'days')
        return {
            day: date.format("DD"),
            yearMonth: date.format("YYYY/MM"),
            hour: it["hour"],
            area: it["area"],
            hourSize: scaleSize(huanWeiData, index, "hour", 11),
            areaSize: scaleSize(huanWeiData, index, "area", 5),
        };
    });
    histogram = {
        conf: {
            root: "images/charts/histogram",
            head: "head.png",
            foot: "foot.png",
            bottom: "bottom.png",
            main: "main.png"
        },
        yArr: [600, 500, 400, 300, 200, 100, 0],
        values: [
            {color: "blue", date: "4/11", value: 367}
            , {color: "blue", date: "4/12", value: 590}
            , {color: "blue", date: "4/13", value: 322}
            , {color: "blue", date: "4/14", value: 419}
            , {color: "blue", date: "4/15", value: 530}
            , {color: "blue", date: "4/16", value: 260}
            , {color: "pink", date: "4/17", value: 200}
        ],
        total: "" + (367 + 590 + 322 + 419 + 530 + 98 + 200),
        lastWeekTotal: "2658"
    }
    linesArr = {
        "1": {color: "#B5CFFF", img: "images/lines/line1.png", siteNum: "6站", name: "1号线路"},
        "2": {color: "#91FFFE", img: "images/lines/line2.png", siteNum: "6站", name: "2号线路"},
        "3": {color: "#E9CE61", img: "images/lines/line3.png", siteNum: "7站", name: "3号线路"},
        "4": {color: "#C2187E", img: "images/lines/line4.png", siteNum: "8站", name: "4号线路"}
    }
    persons = {
        "hxm": {name: "韩小美", img: "images/persons/hxm.png"},
        "fym": {name: "范英明", img: "images/persons/fym.png"},
        "lqj": {name: "李强军", img: "images/persons/lqj.png"},
        "zj": {name: "张杰", img: "images/persons/zj.png"},
    }
    focus = {
        days: ["日", "一", "二", "三", "四", "五", "六"],
        dayIsFocus: [true, false, true, true, false, false, false],
        times: [["11:30", "13:30"], ["17:00", "19:30"]]
    }
    runCars = {
        "B100801": {
            cardNo: "B100801",
            personId: "fym",
            lineNo: "4",
            speed: " 32",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100802": {
            cardNo: "B100802",
            personId: "lqj",
            lineNo: "3",
            speed: "  0",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100808": {
            cardNo: "B100808",
            personId: "zj",
            lineNo: "1",
            speed: " 26",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100809": {
            cardNo: "B100809",
            personId: "hxm",
            lineNo: "2",
            speed: " 60",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100812": {
            cardNo: "B100812",
            personId: "fym",
            lineNo: "1",
            speed: " 16",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        }
    }
    logisticsConf = {
        name: {font: "18px Adobe Heiti Std R", fillColor: "#7E95C6"},
        num: {font: "28px manteka_cyrillicregular", fillColor: "#FFF"}
    }
    logistics = {
        pies: [
            {per: 30, name: "服装", num: 3364},
            {per: 10, name: "纪念品", num: 1120},
            {per: 20, name: "生活用品", num: 1028},
            {per: 40, name: "饮料", num: 1698},
        ],
        total:"312",
        lastWeekTotal: "上周累计：1366.89 万元"
    }
    alarms = {
        today: {num: " 6"},
        week: {totalAlarmNum: 10 + Math.floor(Math.random() * 70)}
    }
    weather = {
        pos: [79, 631],
        temperature: {pos: [123, 85], text: "16", font: '30px bold manteka_cyrillicregular', fillColor: "#FFF"},
        temperatureC: {pos: [160, 85], text: "℃", font: '30px  ArialMT', fillColor: "#AAC2EC"},
        type: {pos: [123, 155], text: "大雨", font: '30px bold manteka_cyrillicregular', fillColor: "#FFF"},
        typeImg: {pos: [48, 145], textures: "images/weather/weather_bigRain.png"},
        windDirect: {pos: [125, 220], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风向:"},
        windDirectText: {pos: [125, 240], font: '20px  Adobe Heiti Std R', fillColor: "#FFF", text: "东南"},
        windPower: {pos: [175, 220], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风力:"},
        windPowerText: {pos: [175, 240], font: '20px  Adobe Heiti Std R', fillColor: "#FFF", text: "9级"},
        warning: {pos: [55, 315], textures: "images/weather/台风预警.png"},
        warningText: {pos: [45, 365], text: "台风预警", font: '16px "MicrosoftYaHeiUI"', fillColor: "#AAC2EC"},
    }
    data = {
        resolution: resolution,
        viewport: [srcW / (srcW / width), srcH / (srcW / width)],
        runingCarConfig: {
            personName: {font: "24px MicrosoftYaHeiUI", fillColor: "#FFF"},
            carNo: {font: "30px manteka_cyrillicregular", fillColor: "#FFF"},// STHeitiSC-Medium
            speed: {font: "36px manteka_cyrillicregular", fillColor: "#45C9C7"},
            speedUnit: {font: "21px STHeitiSC-Light", fillColor: "#6B7CA0"},
            upDate: {font: "20px ArialMT", fillColor: "#6B7CA0"},
            upTime: {font: "33px Digital-7Mono", fillColor: "#9BAFDB"},
        },
        focus,
        runingIndx: ["B100801", "B100802", "B100808", "B100809", "B100812"],
        runCars,
        persons,
        linesArr,
        huanWei: {items: huanWei, lastWeekTotal: "上周累计：136,700㎡"},
        connections: {
            totalNumPerson: "1762",
            "highTimes": "上周高峰时段：6:00-7:00 、18:00-17:00",
            "highDays": `  上周高峰日：${now.add(-1, 'days').format("MM/DD")}、${now.add(-3, 'days').format("MM/DD")}(月/日)`,
        },
        histogram,
        logisticsConf,
        logistics,
        alarms,
        weather,
        now_time: moment().format('YYYY/MM/DD   HH:mm:ss')
    };
    m = new Vue({
        el: "#app",
        data() {
            return data;
        },
        mounted: function () {
            $("#app canvas").css({"z-index": 10000})
            var v = this
            setInterval(function () {
                v.$data.now_time = moment().format('YYYY/MM/DD   HH:mm:ss')
            }, 1000);
        },
        methods: {}
    })
    setInterval(function () {
        try {
            weather["temperature"]["text"] = 12 + Math.floor(4 * Math.random())
            let num = 2 + Math.floor(12 * Math.random());
            alarms.today.num = num < 10 ? " " + num : num;
            alarms.week.totalAlarmNum = num + Math.floor(Math.random() * 70);
            let total = 0;
            for (const his of histogram["values"]) {
                let kNumber = Math.floor(Number(histogram["yArr"][0]) * Math.random());
                total += kNumber
                his["value"] = kNumber
            }
            histogram.total = total + ""

        } catch (e) {
        }
    }, 15000)

    setInterval(function () {
        var carNos = Object.keys(runCars);
        let now = moment();
        let date = now.format("YYYY/MM/DD")
        let time = now.format("hh:mm:ss")

        function upCar(car) {
            let speed = Math.abs(Math.floor(Number(car["speed"]) + Math.ceil((Math.random() > 0.5 ? -1 : 1) * 10 * Math.random())));
            if (speed > 60) {
                speed = Math.floor(70 - 10 * Math.random());
            }
            car["speed"] = speed
            car['upDate'] = date;
            car['upTime'] = time;
        }

        for (const carNo of carNos) {
            let car = runCars[carNo]
            setTimeout(function () {
                upCar(car);
            }, Math.ceil(5000 * Math.random()))
        }
    }, 2000)
})
