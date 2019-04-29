import {PD} from "./PD";
import $ from "jquery";
$(function () {
    let pd= new PD("#container", 700, 700);
    let car = pd.addCar("car1", 100, 100);
    setInterval(function () {
        car.attr({pos:[100+Math.floor(Math.random()*100),100+Math.floor(Math.random()*100)]})
    })
})


