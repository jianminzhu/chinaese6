class ParkMap {
    groupCars;
    cars = {};
    carAnimates = {}
    layerBg

    constructor(sel = "#container", {
        resolution: [width = 3427, heigth = 1920],
        bg = "images/bg2.png",
        map: {bottom = "images/map_bottom.png?v=1", up = "images/map_up.png?v=1", down = "images/map_down.png?v=1"}

    } = {resolution: [3427, 1920], map: {bottom, up, down}}, public spritejs = window.spritejs) {
        try {
            let {Scene, Sprite, Group} = this.spritejs
            const scene = new Scene(sel, {resolution: [width, heigth]})
            var layBg = this.layerBg = scene.layer();
            layBg.append(new Sprite({pos: [0, 0], textures: bg}))
            layBg.append(new Sprite({pos: [371, 158], textures: bottom}))
            let gCars = this.groupCars = new Group({pos: [371, 158]});
            layBg.append(new Sprite({pos: [434, 147], textures: down}))
            layBg.append(gCars);
            layBg.append(new Sprite({pos: [434, 147], textures: up}))
        } catch (e) {
        }
    }

    getCar(id) {
        return this.cars[id];
    }

    addCar({id, pos: [x, y], size: [width = 80, height = 80], textures}) {
        const {Sprite} = this.spritejs
        const car = new Sprite(textures)
        car.attr({
            anchor: [0, 0.3],
            size: [width, height],
            pos: [x, y],
        })
        this.cars[id] = car;
        car.animate(this.genCarImgs(textures), {
            duration: 2000,
            iterations: Infinity,
            direction: 'alternate',
        });
        this.groupCars.append(car);
        return car;
    }

    genCarImgs(img) {
        let prefix = img.replace(/\d{1,2}\.png$/, "")
        var arr = [];
        for (var i = 1; i <= 12; i++) {
            arr.push({textures: `${prefix}${i}.png`})
        }
        return arr;
    };

    move(id, [x, y], durationSecond = 2, isDemo = false) {
        let car = this.cars[id];
        if (isDemo) {
            let paths = [
                {pos: car.attr().pos}
                , {pos: [x, y]}
            ];
            car.animate(paths, {
                iterations: Infinity,
                direction: 'alternate',
                duration: durationSecond * 1000
            });

        }
        car.transition(durationSecond).attr({pos: [x, y]});
    }

    movePoints(id, points, durationSecond = 2) {
        let car = this.cars[id];
        car.animate(points.map(function (v, i) {
            return {pos: v}
        }), {
            iterations: Infinity,
            direction: 'alternate',
            duration: durationSecond * 1000
        });

    }

    moveOffset(id, [x, y], durationSecond = 2000) {
        let car = this.cars[id];
        var srcX = car.attr("x");
        var srcY = car.attr("y");
        this.carAnimates[id] = this.cars[id].transition(durationSecond).attr({pos: [srcX + x, srcY + y]});
    }

}
