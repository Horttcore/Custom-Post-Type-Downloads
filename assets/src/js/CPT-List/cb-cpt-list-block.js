import TaxList from './cb-tax-list';
import TermList from './cb-term-list';

const { Component } = wp.element;
const { __ } = wp.i18n;
const { InspectorControls } = wp.editor;
const { TextControl, SelectControl, RangeControl, withAPIData } = wp.components;

const { withSelect } = wp.data;

class DownloadsListBlock extends Component {
    constructor(){
        super( ...arguments );
    }
    render() {
        const {attributes, setAttributes, className, isSelected, posts} = this.props;
        const { taxonomie, term, amount, orderBy, order } = attributes;
        const classes = ((className) ? className : '' ) + ' list-wrapper ';

        var _posts = [];
        if(posts != undefined && taxonomie != undefined && taxonomie != undefined && term != undefined && term.length != 0){
            for(var i = 0; i < posts.length; i++){
                // check if we have any value set in given taxonomie cause this will be an arry of ids
                if(posts[i][taxonomie] != undefined && posts[i][taxonomie].length > 0){
                    _posts.push(posts[i]);
                }
            }
        } else {
            _posts = posts;
        }
        
        const termList = <TermList
            taxonomie={ taxonomie }
            value={ term }
            onChange={ (newTerm) => {
                var _term = [];
                if(Array.isArray(newTerm)){
                    newTerm.map( (item, i) => {
                        if( _term.indexOf(item) == -1){
                            _term.push(item);
                        }
                    })
                } else {
                    _term.push(newTerm);
                }
                // @todo trigger change cause checked will stuck
                setAttributes( {term: _term} );
            }}
        />
        const inspectorControls = isSelected && (
            <InspectorControls key="inspector">
                <TaxList
                    posttype={ 'download' }
                    value={ taxonomie }
                    onChange={ (newTaxonomie) => {
                        setAttributes( {taxonomie: newTaxonomie} );
                        // reset other related values
                        setAttributes( {term: ''} );
                    }}
                />
                { termList }
                <RangeControl
                    label={ __('Amount')}
                    value={ amount }
                    onChange={ (newAmount) => {
                        setAttributes( {amount: newAmount})
                    }}
                    min={ 0 }
                    max={ 100 }
                />
                <TextControl
                    label={ __('Order By')}
                    value={ orderBy }
                    onChange={ (newOrderBy) => {
                        setAttributes( {orderBy: newOrderBy} )
                    }}
                />
                <SelectControl
                    label={ __('Order') }
                    value={ order }
                    options={[
                        {label: __('Ascending'), value: 'asc'},
                        {label: __('Decending'), value: 'desc'},
                    ]}
                    onChange={ (newOrder) => {
                        setAttributes( {order: newOrder} )
                    }}
                />
            </InspectorControls>
        );

        var hasPosts = Array.isArray( _posts ) && _posts.length;
        if(!hasPosts){
            return [
                inspectorControls,
                <div className={ classes } key="container">
                    { __('No Downloads found') }
                </div>
            ];
        }
        // @todo change edit output
        return [
            inspectorControls,
            <div className={ classes } key="container">
                    { _posts.map( (post, i) => 
                        <div class="downloads-wrapper" key={ i }>
                            <img class="downloads-image" src={ post.thumbnail} />
                            { post.title.rendered }
                        </div>
                    )}
            </div>
        ];
    
    }
}

export default withSelect( (select, props) => {
    const { posttype, taxonomie, term, amount, orderBy, order } = props.attributes;
    const _order = String(order).toLowerCase();
	const postsQuery = {
        per_page: amount,
        order: _order,
        orderby: orderBy,
        _fields: [ 'id', 'date_gmt', 'link', 'title', 'content', 'meta', 'thumbnail'],
    };
    // retrieve taxonomies in fields
    if(taxonomie != undefined){
        postsQuery._fields.push(taxonomie);
    }
    // retrieve posts with a given term
    if(taxonomie != undefined && typeof term != 'undefined' && term.length != 0){
        if(term.indexOf('-1') != -1){
            delete postsQuery[taxonomie];
        } else {
            postsQuery[taxonomie] = term;
        }
    }
    return {
        posts: select('core').getEntityRecords('postType', posttype, postsQuery)
    };
})( DownloadsListBlock );