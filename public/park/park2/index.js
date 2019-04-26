$(function () {
    let srcW = 3427;
    let srcH = 1920;
    let width = 1920;
    let resolution = [srcW, srcH]
    $("body,img,div").each(function () {
        $(this).addClass("bg").css({
            width: `${width}px`,
            height: `${srcH * (width / srcW)}px`
        })
    })
    let today = {
        pos: [2074, 135],
        font: "38px Digital-7Mono",
        fillColor: "#42A6B3",
        text: moment().format('YYYY/MM/DD   HH:mm:ss')
    }
    lines = {
        "1": {siteNum: 9, name: "1号线"},
        "2": {siteNum: 4, name: "2号线"},
        "3": {siteNum: 8, name: "3号线"},
        "4": {siteNum: 6, name: "4号线"},
        "5": {siteNum: 5, name: "5号线"},
        "6": {siteNum: 11, name: "6号线"},
        "7": {siteNum: 7, name: "7号线"},
        "8": {siteNum: 2, name: "8号线"},
        "9": {siteNum: 8, name: "9号线"},
        "10": {siteNum: 15, name: "10号线"},
    }
    person = {
        "ls": {name: "雷生", img: "images/person/ls.png"},
        "xb": {name: "小白", img: "images/person/xb.png"},
        "xz": {name: "小朱", img: "images/person/xz.png"},
        "ss": {name: "森森", img: "images/person/ss.png"}
    }
    runingCars = [
        {cardId: "B100801", personId: "ls", lineNo: "1", speed: "32KM", upDate: "2019/04/26", upTime: "18:35:00"},
        {cardId: "B100802", personId: "xb", lineNo: "1", speed: "0KM", upDate: "2019/04/26", upTime: "18:35:00"},
        {cardId: "B100808", personId: "xz", lineNo: "1", speed: "26KM", upDate: "2019/04/26", upTime: "18:35:00"},
        {cardId: "B100809", personId: "ss", lineNo: "1", speed: "60KM", upDate: "2019/04/26", upTime: "18:35:00"},
        {cardId: "B100812", personId: "ls", lineNo: "1", speed: "16KM", upDate: "2019/04/26", upTime: "18:35:00"}
    ]
    setInterval(function () {
        today["text"] = moment().format('YYYY/MM/DD   HH:mm:ss')
    }, 1000)
    data = {
        resolution: resolution,
        viewport: [srcW / (srcW / width), srcH / (srcW / width)],
        date: today,
        weather: {
            pos: [79, 631],
            temperature: {pos: [123, 90], text: "16", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
            temperatureC: {pos: [160, 90], text: "℃", font: '30px  ArialMT', fillColor: "#AAC2EC"},
            type: {pos: [123, 160], text: "大雨", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
            typeImg: {pos: [55, 145], textures: "images/weather/weather_bigRain.png"},
            windDirect: {pos: [135, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风向:"},
            windDirectText: {pos: [135, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "东南"},
            windPower: {pos: [185, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风力:"},
            windPowerText: {pos: [185, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "9级"},
            warning: {pos: [55, 325], textures: "images/weather/台风预警.png"},
            warningText: {pos: [45, 375], text: "台风预警", font: '16px "MicrosoftYaHeiUI"', fillColor: "#AAC2EC"},
        },
        runningCars: {},
        lines: [
            {
                group: {pos: [80, 198], ars: [0.5, 0.5]},
                img: {pos: [40, 80],id:"line6", textures: 'images/line/line6.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 8号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: "10站"}
            }, {
                group: {pos: [80, 238]},
                img: {pos: [40, 80],id:"line2", textures: 'images/line/line2.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "19号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 8站"}
            }, {
                group: {pos: [80, 278]},
                img: {pos: [40, 80],id:"line3", textures: 'images/line/line3.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "10号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 2站"}
            }, {
                group: {pos: [80, 318]},
                img: {pos: [40, 80],id:"line4", textures: 'images/line/line4.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 6号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }, {
                group: {pos: [80, 358]},
                img: {pos: [40, 80],id:"line4", textures: 'images/line/line4.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "11号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
            }, {
                group: {pos: [80, 398]},
                img: {pos: [40, 80],id:"line6", textures: 'images/line/line6.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "12号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }, {
                group: {pos: [80, 438]},
                img: {pos: [40, 80],id:"line1", textures: 'images/line/line1.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "1号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
            }, {
                group: {pos: [80, 478]},
                img: {pos: [40, 80],id:"line5", textures: 'images/line/line5.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "2号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }]
    };
    m = new Vue({
        el: "#app",
        data() {
            return data ;
        },
        methods: {}
    })
})
