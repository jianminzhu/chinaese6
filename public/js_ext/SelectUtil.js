/**
 *
 * @param id  网页中的 元素   <select id="ssss"></select>
 * @param  data  option 的数组数据    示例 [{name:"管理员",value:"1" },{name:"操作员",value:"2" }]
 * @param valueKey  option中value的值 通过什么key 获取
 * @param  showKey  option中显示值 通过什么key 获取
 * @param  selectedValue 选择的项目
 */
function GenSelectOption($it, data, valueKey, showKey, selectValue) {
    if (data === void 0) { data = []; }
    if (showKey === void 0) { showKey = valueKey; }
    if (selectValue === void 0) { selectValue = undefined; }
    var options = [];
    for (var i = 0; i < data.length; i++) {
        var row = data[i];
        options[options.length] = "<option value=\"" + row[valueKey] + "\" >" + row[showKey] + "</option>";
    }
    $it.innerHTML = options.join("");
    if (selectValue != undefined) {
        $it.value = selectValue;
    }
}
function GenSelect2(main, sencod, data, valueKey, showKey, selectValue) {
    if (data === void 0) { data = []; }
    if (showKey === void 0) { showKey = valueKey; }
    console.log(document.getElementsByName(main)[0]);
}
