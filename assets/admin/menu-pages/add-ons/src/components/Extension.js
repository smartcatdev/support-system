import PropTypes from 'prop-types'
import Thumbnail from './Thumbnail'
import LicenseStatus from './LicenseStatus'
import LicenseContainer from '../containers/LicenseContainer'
import { pro_installed } from '../data'
import strings from '../localize'
import decode from 'unescape'
import './Extension.scss'

const Extension = ({ license, extension }) => {
    let element = (
        <div className="extension">
            <Thumbnail src={ extension.thumbnail } draft={ extension.status === 'draft' } />
            <div className="info">
                <h3 className="title">
                    { decode(extension.title) } { license ? <LicenseStatus status={ license.status } /> : '' }
                </h3>
                <p className="excerpt">{ extension.excerpt }</p>
                { license ? <LicenseContainer license={ license } extension={ extension } /> : '' }
            </div>
            <div className="actions">
                { !license 
                    ? extension['pro_add-on'] && pro_installed
                        ? <button className="button cta" disabled>{  strings.installed }</button> 
                        : <a className="button-primary cta" href={ extension.link } target="_blank">{ strings.get_add_on }</a>
                    : '' }
            </div>
            <div className="clear" />
        </div>
    )   

    if (license) {
        return wrap(element, 'is-installed')
    } else if (extension.status === 'draft' ) {
        return wrap(element, 'draft')
    }

    return wrap(element, 'advertisment')
}

const wrap = (element, className) => {
    return <div className={ className }>{ element }</div>
}

export default Extension
