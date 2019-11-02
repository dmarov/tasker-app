import webpack from 'webpack';
import merge from 'webpack-merge';
import common from './webpack.common';
import path from 'path';

const config: webpack.Configuration = merge(common, {
    mode: 'development',
    devServer: {
        port: 9000,
        contentBase: path.join(__dirname, 'dist'),
    }
});

export default config;
