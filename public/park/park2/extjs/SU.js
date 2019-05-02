var SU = /** @class */ (function () {
    function SU() {
    }
    SU.prototype.add = function (layer, type, attr) {
        var typeMapping = {
            "s": "Sprite",
            "p": "Path",
            "l": "Label",
            "g": "Group"
        };
        var it = eval("new " + typeMapping[type] + "(attr)");
        layer.append(it);
        return it;
    };
    return SU;
}());
