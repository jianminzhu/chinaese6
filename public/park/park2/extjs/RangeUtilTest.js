"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var RangeUtil_1 = require("./RangeUtil");
console.log(RangeUtil_1.RangeUtil.range(1, 11, function (i) {
    return "car" + i + ".png";
}));
