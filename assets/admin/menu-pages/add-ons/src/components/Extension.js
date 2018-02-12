import PropTypes from 'prop-types'
import Thumbnail from './Thumbnail'
import LicenseStatus from './LicenseStatus'
import LicenseContainer from '../containers/LicenseContainer'
import { pro_installed } from '../data'
import strings from '../localize'
import decode from 'unescape'
import './Extension.scss'

const Extension = ({ license, extension }) => {
    return (
        <li className="extension">
            <Thumbnail src={ extension.thumbnail } />
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
                        ? <button className="button" disabled>{  strings.installed }</button> 
                        : <a className="button-primary" href={ extension.link } target="_blank">{ strings.get_add_on }</a>
                    : '' }
            </div>
            <div className="clear" />
        </li>
    )   
}

export default Extension
