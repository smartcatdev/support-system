import Extension from '../components/Extension'

const mapStateToProps = (state, ownProps) => {
    return {
        license: state.find(license => license.item_name == ownProps.extension.title)
    }
}

export default ReactRedux.connect(mapStateToProps)(Extension)