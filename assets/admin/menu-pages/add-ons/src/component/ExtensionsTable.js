class ExtensionsTable extends React.Component {
    render() {
        return JSON.stringify(this.props.extensions)
    }
}

const mapStateToProps = (state) => {
    return {
        extensions: state.extensions
    }
}

export default ReactRedux.connect(mapStateToProps)(ExtensionsTable)