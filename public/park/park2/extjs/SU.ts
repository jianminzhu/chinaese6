class SU {
    add(layer,type, attr) {
        let typeMapping={
            "s": "Sprite",
            "p": "Path",
            "l": "Label",
            "g": "Group"
        }
        let it = eval("new " + typeMapping[type] + "(attr)");
        layer.append(it)
        return it;
    }
}