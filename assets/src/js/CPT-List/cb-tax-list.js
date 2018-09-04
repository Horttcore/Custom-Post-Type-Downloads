//import { filter, includes } from 'lodash';

const { __ } = wp.i18n;
const { SelectControl } = wp.components;
const { withSelect } = wp.data;

function TaxList( {taxonomies, posttype, value, onChange} ){
    var _taxonomies = [];
    if(taxonomies != undefined){
         var arr = Object.values(taxonomies);
        arr.forEach(element => {
            if(element.types.includes(posttype)){
                _taxonomies.push({label: element.name, value: element.slug})
            }
        });
    }
    const hasTaxs = Array.isArray(_taxonomies) && _taxonomies.length;
    if(!hasTaxs){
        return [
            <div></div>
        ];
    }

    _taxonomies.unshift({label: __('All'), value: '-1'});
    return (
        <SelectControl
        { ...{ onChange } }
            label={ __('Custom Posttypes Taxonomies') }
            options={_taxonomies}
            value={ value }
        />
    );
}

export default withSelect( (select, props) => {
    const taxonomies = select('core').getTaxonomies();
    //const postTypeTaxonomies = filter( taxonomies, (taxonomy) => includes(taxonomy.types, props.posttype));
    //const visiblePostTypeTaxonomies = filter( postTypeTaxonomies, (taxonomy) => taxonomy.visibility.show_ui );
    return {
        taxonomies: taxonomies, //visiblePostTypeTaxonomies,
    }
})(TaxList);