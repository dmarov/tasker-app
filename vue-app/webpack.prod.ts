import webpack from 'webpack';
import merge from 'webpack-merge';
import common from './webpack.common';
import TerserPlugin from 'terser-webpack-plugin';

const config: webpack.Configuration = merge(common, {
    mode: 'production',
    optimization: {
        minimizer: [
            new TerserPlugin({
                cache: true,
                parallel: true,
                extractComments: true,
            }),
        ],
        splitChunks: {
            chunks: 'all',
        },
    },
});

export default config;
