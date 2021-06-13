const path = require('path');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const WebpackShellPluginNext = require('webpack-shell-plugin-next');
const DotenvPlugin = require('webpack-dotenv-plugin');
const {VueLoaderPlugin} = require('vue-loader');
const {HotModuleReplacementPlugin} = require('webpack');

let conf = {
    entry: './frontend/src/js/init.js',
    output: {
        path: path.resolve(__dirname, './frontend/assets'),
        filename: 'js/main.min.js',
        publicPath: 'frontend/assets/'
    },
    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                uglifyOptions: {
                    output: {
                        comments: false
                    }
                }
            }),
            new OptimizeCSSAssetsPlugin({
                cssProcessorPluginOptions: {
                    preset: ['default', {discardComments: {removeAll: true}}],
                }
            })
        ]
    },
    devServer: {
        overlay: true,
        historyApiFallback: true
    },
    module: {
        rules: [
            {
                test: /\.(js|vue)$/,
                use: 'eslint-loader',
                enforce: 'pre'
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    presets: ['@babel/preset-env']
                }
            },
            {
                test: /.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader'
                ],
            },
            {
                test: /\.(png|jpe?g|svg)$/,
                use: [{
                    loader: 'url-loader',
                    options: {
                        limit: 8000, // Convert images < 8kb to base64 strings
                        name: '[name].[ext]',
                        publicPath: '../images',
                        outputPath: '/images'
                    }
                }]
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2)$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        publicPath: '../fonts',
                        outputPath: '/fonts'
                    }
                }]
            }
        ]
    },
    resolve: {
        alias: {
            'vue$': process.env.NODE_ENV == 'production' ? 'vue/dist/vue.min.js' : 'vue/dist/vue.esm.js'
        },
        extensions: ['*', '.js', '.vue', '.json']
    },
    plugins: [
        new HotModuleReplacementPlugin(),
        new VueLoaderPlugin(),
        new HtmlWebpackPlugin({
            filename: path.resolve(__dirname, 'index.html'),
            template: path.resolve(__dirname, './frontend/src/template.html'),
            inject: false,
            hash: true,
            xhtml: true
        }),
        new MiniCssExtractPlugin({
            filename: 'css/main.min.css'
        }),
        new CopyWebpackPlugin([
            {from: './frontend/src/images', to: 'images'},
            {from: './frontend/src/fonts', to: 'fonts'}
        ]),
        new WebpackShellPluginNext({
            onBuildExit:{
                scripts: ['./webpack.sh'],
                blocking: false,
                parallel: true
            }
        }),
        new DotenvPlugin({
            path: __dirname + '/frontend/src/js/.env',
            sample: __dirname + '/frontend/src/js/.env.default',
            allowEmptyValues: true
        }),
    ]
};

module.exports = (env, options) => {
    const production = options.mode === 'production';
    conf.devtool = production ? false : 'cheap-module-eval-source-map';
    return conf;
};