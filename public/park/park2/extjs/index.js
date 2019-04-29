var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
var _a = require('spritejs'), Scene = _a.Scene, Sprite = _a.Sprite;
(function () {
    return __awaiter(this, void 0, void 0, function () {
        function cars(carImgs) {
            var s = new Sprite(carImgs[0]);
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
                { offsetDistance: 0 },
                { offsetDistance: 1 },
            ], {
                duration: 3000,
                direction: 'alternate',
                iterations: Infinity,
            });
            s.animate([
                { scale: [0.5, 0.5], offsetRotate: 'auto' },
                { scale: [0.5, -0.5], offsetRotate: 'reverse' },
                { scale: [0.5, 0.5], offsetRotate: 'auto' },
            ], {
                duration: 6000,
                iterations: Infinity,
                easing: 'step-end',
            });
            var toTextures = function (imgs) {
                var textures = [];
                for (var _i = 0, imgs_1 = imgs; _i < imgs_1.length; _i++) {
                    var img = imgs_1[_i];
                    textures.push({ textures: img });
                }
                return textures;
            };
            s.animate(toTextures(carImgs), {
                duration: 500,
                direction: 'alternate',
                iterations: Infinity,
            });
            return s;
        }
        function tick() {
            stage.update();
            layer.draw(false);
            requestAnimationFrame(tick);
        }
        var scene, layer, birdsJsonUrl, birdsRes, rangeArray, s, util, Stage, Curve, motion, randomColor, stage;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    scene = new Scene('#container', {
                        resolution: [1540, 600],
                        viewport: 'auto',
                    });
                    layer = scene.layer('fglayer', {
                        autoRender: false,
                    });
                    birdsJsonUrl = 'images/cars.json';
                    birdsRes = 'images/cars.png';
                    return [4 /*yield*/, scene.preload([birdsRes, birdsJsonUrl])];
                case 1:
                    _a.sent();
                    rangeArray = function (start, end) { return Array(end - start + 1).fill(0).map(function (v, i) { return 'car' + (i + start) + ".png"; }); };
                    s = cars(rangeArray(0, 11));
                    layer.appendChild(s);
                    util = {
                        random: function (min, max) {
                            return min + Math.floor(Math.random() * (max - min + 1));
                        },
                        randomColor: function () {
                            return ['#22CAB3', '#90CABE', '#A6EFE8', '#C0E9ED', '#C0E9ED', '#DBD4B7', '#D4B879', '#ECCEB2', '#F2ADA6', '#FF7784'][util.random(0, 9)];
                        },
                    };
                    Stage = curvejs.Stage, Curve = curvejs.Curve, motion = curvejs.motion;
                    randomColor = util.randomColor, stage = new Stage(layer.canvas);
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
                    tick();
                    return [2 /*return*/];
            }
        });
    });
}());
