export const licenses = ucare_addons_l10n.vars.licenses

export const extensions = ucare_addons_l10n.vars.products.sort(extension => {
    if (extension.status === 'draft') {
        return -10
    }

    if (licenses.find(license => license.item_name === extension.title)) {
        return -5
    }
})