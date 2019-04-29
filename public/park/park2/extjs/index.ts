const {Scene, Sprite} = require('spritejs')
;(async function () {
    const scene = new Scene('#container', {
        resolution: [1540, 600],
        viewport: 'auto',
    });
    const layer = scene.layer('fglayer', {
        autoRender: false,
    });
    const birdsJsonUrl = 'images/cars.json'
    const birdsRes = 'images/cars.png'
    await scene.preload([birdsRes, birdsJsonUrl]);
    function cars(carImgs) {
        const s = new Sprite(carImgs[0]);
        s.attr({
            anchor: [0.5, 0.5],
            pos: [300, 100],
            transform: {
                scale: [0.5, 0.5],
            },
            offsetPath: 'M10,80 q100,120 120,20 q140,-50 160,0',
            zIndex: 200,
        });
        s.animate([
            {offsetDistance: 0},
            {offsetDistance: 1},
        ], {
            duration: 3000,
            direction: 'alternate',
            iterations: Infinity,
        });

        s.animate([
            {scale: [0.5, 0.5], offsetRotate: 'auto'},
            {scale: [0.5, -0.5], offsetRotate: 'reverse'},
            {scale: [0.5, 0.5], offsetRotate: 'auto'},
        ], {
            duration: 6000,
            iterations: Infinity,
            easing: 'step-end',
        });
        let toTextures = function (imgs) {
            let textures = [];
            for (const img of imgs) {
                textures.push({textures: img});
            }
            return textures;
        }
        s.animate(toTextures(carImgs), {
            duration: 500,
            direction: 'alternate',
            iterations: Infinity,
        });
        return s;
    }

    let rangeArray = (start, end) => Array(end - start + 1).fill(0).map((v, i) => 'car' + (i + start) + ".png")
    const s = cars(rangeArray(0, 11));
    layer.appendChild(s);
    const util = {
        random(min, max) {
            return min + Math.floor(Math.random() * (max - min + 1));
        },
        randomColor() {
            return ['#22CAB3', '#90CABE', '#A6EFE8', '#C0E9ED', '#C0E9ED', '#DBD4B7', '#D4B879', '#ECCEB2', '#F2ADA6', '#FF7784'][util.random(0, 9)];
        },
    };

    const {Stage, Curve, motion} = curvejs;

    const randomColor = util.randomColor,
        stage = new Stage(layer.canvas);

    stage.add(new Curve({
        points: [378, 123, 297, 97, 209, 174, 217, 258],
        color: randomColor(),
        motion: motion.rotate,
        data: Math.PI / 20,
    }));

    stage.add(new Curve({
        points: [378, 123, 385, 195, 293, 279, 217, 258],
        color: randomColor(),
        motion: motion.rotate,
        data: Math.PI / 20,
    }));

    function tick() {
        stage.update();
        layer.draw(false);
        requestAnimationFrame(tick);
    }

    tick();
}());
