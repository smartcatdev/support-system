export const licenses = ucare_addons_l10n.vars.licenses
export const rest_url = ucare_addons_l10n.vars.rest_url
export const wp_nonce = ucare_addons_l10n.vars.wp_nonce

export const extensions = ucare_addons_l10n.vars.products.sort(extension => {
    if (extension.status === 'draft') {
        return 10
    }

    if (licenses.find(license => license.item_name === extension.title)) {
        return -5
    }

    return 0
})