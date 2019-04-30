class PardAdv{
    layer;
    cars = {};
    constructor(public id,public width,public heigth) {
        try {
            const {Scene, Sprite} = spritejs
            const scene = new Scene(id, {viewport: [width, heigth], resolution: [width, heigth]})
            this.layer = scene.layer()
        } catch (e) {
            alert(e.toString())
        }
    }
    getCar(id) {
        return this.cars[id];
    }
    
     genCarImgs(img) {
        let prefix = img.replace(/\d{1,2}\.png$/, "")
         var arr = [];
         for (var i = 1; i <= 12; i++) {
             arr.push({textures: `${prefix}${i + 1}.png`})
         }
         return arr;
    };
    
    addCar(id,x=0,y=0,pic='./park2/images/cars/pink/1.png') {
        const { Sprite} = spritejs
        const car = new Sprite(pic)
        car.attr({
            anchor: [0, 0.3],
            size: [60, 40],
            pos: [x,y],
            borderRadius: 50,
        })
        this.cars[id] = car;
        v=this;
        setTimeout(function () {
            car.animate(v.genCarImgs(pic), {
                duration: 1000,
                iterations: Infinity,
                direction: 'alternate',
            });
        },1000 * Math.random());
        this.layer.append(car);
        return car;
    }

    moveCar(id, x, y) {
        this.cars[id].attr({ pos: [x, y ]});
    }
    moveOffset(id, x=0, y=0) {
        let car = this.cars[id];
        var srcX =  car.attr("x");
        var srcY =  car.attr("y");
        car.attr({pos: [srcX + x, srcY + y]});
    }

}