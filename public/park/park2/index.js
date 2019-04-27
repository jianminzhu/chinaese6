$(function () {
    function wait(ms) {
        return new Promise((resolve) => {
            setTimeout(resolve, ms)
        })
    }
    let srcW = 3427;
    let srcH = 1920;
    let width = 1920;
    let resolution = [srcW, srcH]
    $("body,body>svg,body>img,body>div").each(function () {
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
    cars = {
        car1: {id:"car1",pos: [851, 353], textures:"images/cars/violet/quan_violet_01.png"  },
        car2: {id:"car2",pos: [851, 403], textures:"images/cars/blue/quan_blue_01.png"  },
        car3: {id:"car3",pos: [850, 323], textures:"images/cars/pink/quan_01.png"  },
        car4: {id:"car4",pos: [850, 303], textures:"images/cars/green/quan_01.png"  },
    }

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
            , {color: "blue", date: "4/16", value: 98}
            , {color: "pink", date: "4/17", value: 200}
        ],
        total: "" + (367 + 590 + 322 + 419 + 530 + 98 + 200),
        lastWeekTotal: "2658"
    }
    linesArr = {
        "1": {img: "images/lines/line1.png", siteNum: 9, name: "1号线"},
        "2": {img: "images/lines/line2.png", siteNum: 4, name: "2号线"},
        "3": {img: "images/lines/line3.png", siteNum: 8, name: "3号线"},
        "4": {img: "images/lines/line4.png", siteNum: 6, name: "4号线"},
        "5": {img: "images/lines/line5.png", siteNum: 5, name: "5号线"},
        "6": {img: "images/lines/line6.png", siteNum: 11, name: "6号线"}
    }
    persons = {
        "ls": {name: "雷生", img: "images/persons/ls.png"},
        "xb": {name: "小白", img: "images/persons/xb.png"},
        "xz": {name: "小朱", img: "images/persons/xz.png"},
        "ss": {name: "森森", img: "images/persons/ss.png"}
    }
    runCars = {
        "B100801": {
            cardNo: "B100801",
            personId: "ls",
            lineNo: "4",
            speed: " 32",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100802": {
            cardNo: "B100802",
            personId: "xb",
            lineNo: "3",
            speed: "  0",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100808": {
            cardNo: "B100808",
            personId: "xz",
            lineNo: "5",
            speed: " 26",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100809": {
            cardNo: "B100809",
            personId: "ss",
            lineNo: "6",
            speed: " 60",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        },
        "B100812": {
            cardNo: "B100812",
            personId: "ls",
            lineNo: "1",
            speed: " 16",
            upDate: "2019/04/26",
            upTime: "18:35:00"
        }
    }
    logisticsConf = {
        name: {font: "18px Adobe Heiti Std R", fillColor: "#7E95C6"},
        num: {font: "28px MantekaCyrillic-Regular", fillColor: "#FFF"}
    }
    logistics = [
        {per: 30, name: "服装　", num: 3344},
        {per: 10, name: "纪念品", num: 1120},
        {per: 20, name: "生活用品", num: 10028},
        {per: 40, name: "饮料　", num: 16998},
    ]
    alarms = {
        today: {num: " 6"}
    }
    weather = {
        pos: [79, 631],
        temperature: {pos: [123, 90], text: "16", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
        temperatureC: {pos: [160, 90], text: "℃", font: '30px  ArialMT', fillColor: "#AAC2EC"},
        type: {pos: [123, 160], text: "大雨", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
        typeImg: {pos: [48, 145], textures: "images/weather/weather_bigRain.png"},
        windDirect: {pos: [135, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风向:"},
        windDirectText: {pos: [135, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "东南"},
        windPower: {pos: [185, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风力:"},
        windPowerText: {pos: [185, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "9级"},
        warning: {pos: [55, 325], textures: "images/weather/台风预警.png"},
        warningText: {pos: [45, 375], text: "台风预警", font: '16px "MicrosoftYaHeiUI"', fillColor: "#AAC2EC"},
    }
    data = {
        resolution: resolution,
        viewport: [srcW / (srcW / width), srcH / (srcW / width)],
        runingCarConfig: {
            personName: {font: "24px MicrosoftYaHeiUI", fillColor: "#FFF"},
            carNo: {font: "30px  STHeitiSC-Medium", fillColor: "#FFF"},
            speed: {font: "36px MantekaCyrillic-Regular", fillColor: "#45C9C7"},
            speedUnit: {font: "21px STHeitiSC-Light", fillColor: "#6B7CA0"},
            upDate: {font: "20px ArialMT", fillColor: "#6B7CA0"},
            upTime: {font: "33px Digital-7Mono", fillColor: "#9BAFDB"},
        },
        runingIndx: ["B100801", "B100802", "B100808", "B100809", "B100812"],
        runCars,
        persons,
        linesArr,
        histogram,
        logisticsConf,
        logistics,
        alarms,
        date: today,
        weather,

        lines: [
            {
                group: {pos: [80, 198]},
                img: {pos: [40, 80], id: "line6", textures: 'images/lines/line6.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 8号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: "10站"}
            }, {
                group: {pos: [80, 238]},
                img: {pos: [40, 80], id: "line2", textures: 'images/lines/line2.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "19号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 8站"}
            }, {
                group: {pos: [80, 278]},
                img: {pos: [40, 80], id: "line3", textures: 'images/lines/line3.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "10号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 2站"}
            }, {
                group: {pos: [80, 318]},
                img: {pos: [40, 80], id: "line4", textures: 'images/lines/line4.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 6号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }, {
                group: {pos: [80, 358]},
                img: {pos: [40, 80], id: "line4", textures: 'images/lines/line4.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "11号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
            }, {
                group: {pos: [80, 398]},
                img: {pos: [40, 80], id: "line6", textures: 'images/lines/line6.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "12号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }, {
                group: {pos: [80, 438]},
                img: {pos: [40, 80], id: "line1", textures: 'images/lines/line1.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "1号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
            }, {
                group: {pos: [80, 478]},
                img: {pos: [40, 80], id: "line5", textures: 'images/lines/line5.png'},
                line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "2号线路"},
                site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
            }]
    };
    m = new Vue({
        el: "#app",
        data() {
            return data;
        },
        methods: {}
    })
    setInterval(function () {
        try {
            weather["temperature"]["text"] = 10 + Math.floor(28 * Math.random())
            let num = Math.floor(68 * Math.random());
            alarms.today.num = num < 10 ? " " + num : num;
            let total = 0;
            for (const his of histogram["values"]) {
                let kNumber = Math.floor(Number(histogram["yArr"][0]) * Math.random());
                total += kNumber
                his["value"] = kNumber
                console.log(his["value"])
            }
            histogram.total = total + ""

        } catch (e) {
        }
    }, 1000)
    setInterval(function () {
        try {
            for (const his of logistics) {
                let per = Math.floor(100 * Math.random())
                if (per > 90) {
                    per = 90
                }
                his["per"] = per
            }
        } catch (e) {
        }
    }, 1000)

    setInterval(function () {
        today["text"] = moment().format('YYYY/MM/DD   HH:mm:ss')
    }, 1000)
    setInterval(function () {
        var carNos = Object.keys(runCars);
        let now = moment();
        let date = now.format("YYYY/MM/DD")
        let time = now.format("hh:mm:ss")

        function upCar(car) {
            let speed = Math.abs(Math.floor(Number(car["speed"]) + Math.ceil((Math.random() > 0.5 ? -1 : 1) * 10 * Math.random())));
            if (speed > 100) {
                speed = Math.floor(100 - 10 * Math.random());
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
    // setInterval(function () {
    //         var a=m.$refs.rcars[0]
    //         let car = cars["car1"];
    //         var index=car["index"]||1
    //         if (index > 100) {
    //             index = 0;
    //         }
    //         var pos= a.attr().pos
    //         a.attr({pos: [++pos[0],++pos[1]],textures : `0${index++%10}.png`})
    //
    //     console.log( car["textures"]);
    // }, 300)



    // a=m.$refs.rcars[0]
    // rangeGen=function(start, end, fun) {
    //     return Array(end - start + 1).fill(0).map((v, i) => fun(i + start))
    // }
    // a.animate(rangeGen(1, 12, function (i) {
    //     var color = "violet";
    //     return {"textures":`images/cars/${color}/quan_${color}_${i<10?"0":""}${i}.png`};
    // }, {
    //     duration: 500,
    //     direction: 'alternate',
    //     iterations: Infinity,
    // }));
    cars.car1.pos=[cars.car1.pos[0]+10,cars.car1.pos[1]+10]




})
