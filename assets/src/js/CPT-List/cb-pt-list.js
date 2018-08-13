const { __ } = wp.i18n;
const { SelectControl, withAPIData } = wp.components;

function PtList( {types, value, onChange} ){
    var _types = [];
    if(types.data != undefined){
        var arr = Object.values(types.data);
        arr.forEach(element => {
            _types.push({ label: element.name, value: [element.rest_base, element.slug] })
        });
    }
    return (
        <SelectControl
        { ...{ onChange } }
            label={ __('Custom Posttypes') }
            options={_types}
            value={ value }
        />
    );
}

export default withAPIData( (props) => {
    return {
        types: '/wp/v2/types',
    };
})( PtList );