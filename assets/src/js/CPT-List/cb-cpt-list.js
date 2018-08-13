import DownloadsListBlock from './cb-cpt-list-block';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('horttcore/downloads-list', {
    title: __('Downloads Listing'),
    icon: 'list-view',
    category: 'widgets',
    description: 'Display a list of a selected downloads',
    attributes: {
        posttype: {
            type: 'array',
            default: ['download'],
        },
        taxonomie: {
            type: 'array',
            default: ['unkategorisiert'],
        },
        term: {
            type: 'array'
        },
        amount: {
            type: 'number',
            default: '1',
        },
        orderBy: {
            type: 'string',
            default: 'id',
        },
        order: {
            type: 'string',
            default: 'ASC',
        },
    },
    edit: DownloadsListBlock,
    save() { return null; }
})