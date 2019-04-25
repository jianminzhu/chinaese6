let resolution = [3427, 1920]
let d = {
    resolution: resolution,
    viewport: resolution,
    date: {},
    weather: {
        pos: [79, 631],
        temperature: {pos: [123, 90], text: "16", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
        temperatureC: {pos: [160, 90], text: "C", font: '30px  ArialMT', fillColor: "#AAC2EC"},
        type: {pos: [123, 160], text: "大雨", font: '30px bold MantekaCyrillic-Regular', fillColor: "#FFF"},
        typeImg: {pos: [55, 145], textures: "images/weather/weather_bigRain.png"},
        windDirect: {pos: [135, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风向:"},
        windDirectText: {pos: [135, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "东南"},
        windPower: {pos: [185, 235], font: '12px "Adobe Heiti Std R"', fillColor: "#AAC2EC", text: "风力:"},
        windPowerText: {pos: [185, 255], font: '18px "Adobe Heiti Std R"', fillColor: "#FFF", text: "9级"},
        warning: {pos: [55, 325], textures: "images/weather/台风预警.png"},
        warningText: {pos: [45, 375], text: "台风预警", font: '16px "MicrosoftYaHeiUI"', fillColor: "#AAC2EC"},
    },
    lines: [{
        group: {pos: [80, 208]},
        img: {pos: [40, 80], textures: 'images/line/line6.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 8号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: "10站"}
    }, {
        group: {pos: [80, 248]},
        img: {pos: [40, 80], textures: 'images/line/line2.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "19号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 8站"}
    }, {
        group: {pos: [80, 288]},
        img: {pos: [40, 80], textures: 'images/line/line3.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "10号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 2站"}
    }, {
        group: {pos: [80, 328]},
        img: {pos: [40, 80], textures: 'images/line/line4.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: " 6号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
    }, {
        group: {pos: [80, 368]},
        img: {pos: [40, 80], textures: 'images/line/line4.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "11号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
    }, {
        group: {pos: [80, 408]},
        img: {pos: [40, 80], textures: 'images/line/line6.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "12号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
    }, {
        group: {pos: [80, 448]},
        img: {pos: [40, 80], textures: 'images/line/line1.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "1号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 6站"}
    }, {
        group: {pos: [80, 488]},
        img: {pos: [40, 80], textures: 'images/line/line5.png'},
        line: {pos: [76, 80], font: '27px bold MicrosoftYaHeiUI-Bold', fillColor: "#FFF", text: "2号线路"},
        site: {pos: [200, 86], font: "18px bold  MicrosoftYaHeiUILight", fillColor: "#B5CFFF", text: " 9站"}
    }]
};
new Vue({
    el: "#app",
    data() {
        return d
    },
    methods: {}
})