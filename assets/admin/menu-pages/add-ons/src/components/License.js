import strings from '../localize'
import './License.scss'

const License = ({ license, extension, onKeyChange, onActivate, onDeactivate }) => {
    let is_valid = license.status === 'valid'
    let renewal  = is_valid? `${extension.checkout_uri}?edd_license_key=${license.key}` : ''

    return (
        <div className="product-license">
            <p className="input-group">
                <label>
                    <span className="label">{ strings.license }</span>
                    <input type="text" 
                           className="form-field" 
                           value={ license.key } 
                           onChange={ onKeyChange } 
                           disabled={ is_valid } />
                    <p className="description expiration">{ license.expiration }</p>
                </label>
            </p>
            { license.last_error
                ? <p className="input-group error-message">
                    <span className="label"></span>
                    <span className="form-field">
                        <span className="dashicons dashicons-warning" /> { license.last_error }
                    </span>
                  </p>
                : ''
            }
            <div className="manage">
                { is_valid
                    ?  <button className="deactivate button" onClick={ onDeactivate }>{ strings.deactivate }</button>
                    :  <button className="activate button-primary" onClick={ onActivate } disabled={ license.key.length === 0 }>{ strings.activate }</button>
                } 
                { renewal
                    ? <a target="_blank" href={ renewal } className="button renew">{ strings.renew }</a>
                    : ''
                }
                <span className={ `spinner ${license.action_pending ? 'is-active' : ''}` } />
            </div>
        </div>
    )
}

export default License