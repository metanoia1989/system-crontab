layui.define(function (exports) {
    var tool = {
        array_column: function array_column(input, column_key, index_key = '') {
            let data = index_key ? {} : [];
            input.forEach((val, key) => {
                if (val.hasOwnProperty(column_key)) {
                    index_key ? data[val[index_key]] = val[column_key] : data.push(val[column_key]);
                }
            });
            return data;
        }
    };
    exports('tool', tool);
});
