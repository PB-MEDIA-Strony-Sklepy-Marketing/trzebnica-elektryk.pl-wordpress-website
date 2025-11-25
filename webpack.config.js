/**
 * Webpack Configuration for Voltmont Website
 * 
 * Production-ready webpack setup with optimization
 * 
 * @package trzebnica-elektryk.pl
 * @since 2.0.0
 */

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
    mode: isProduction ? 'production' : 'development',
    
    entry: {
        main: './src/js/main.js',
        admin: './src/js/admin.js',
    },
    
    output: {
        path: path.resolve(__dirname, 'dist/wp-content/themes/hubag-child/assets'),
        filename: 'js/[name].bundle.js',
        publicPath: '/wp-content/themes/hubag-child/assets/',
    },
    
    devtool: isProduction ? 'source-map' : 'eval-source-map',
    
    module: {
        rules: [
            // JavaScript/JSX
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            ['@babel/preset-env', {
                                targets: {
                                    browsers: ['> 1%', 'last 2 versions', 'not dead']
                                },
                                useBuiltIns: 'usage',
                                corejs: 3
                            }]
                        ],
                        plugins: ['@babel/plugin-transform-runtime']
                    }
                }
            },
            
            // CSS/SCSS
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    isProduction ? MiniCssExtractPlugin.loader : 'style-loader',
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                            importLoaders: 2
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                plugins: [
                                    'autoprefixer',
                                    'cssnano'
                                ]
                            }
                        }
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            },
            
            // Images
            {
                test: /\.(png|jpe?g|gif|svg|webp)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'images/[name][ext]'
                },
                parser: {
                    dataUrlCondition: {
                        maxSize: 10 * 1024 // 10kb - inline smaller images
                    }
                }
            },
            
            // Fonts
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'fonts/[name][ext]'
                }
            }
        ]
    },
    
    plugins: [
        // Clean output directory before build
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: ['js/**/*', 'css/**/*']
        }),
        
        // Extract CSS to separate files
        new MiniCssExtractPlugin({
            filename: 'css/[name].css',
            chunkFilename: 'css/[id].css'
        }),
        
        // BrowserSync for development
        ...(!isProduction ? [
            new BrowserSyncPlugin({
                proxy: 'http://trzebnica-elektryk.local', // Change to your local URL
                files: [
                    'dist/wp-content/themes/hubag-child/**/*.php',
                    'dist/wp-content/themes/hubag-child/**/*.css',
                    'dist/wp-content/themes/hubag-child/**/*.js'
                ],
                notify: false,
                open: false
            })
        ] : [])
    ],
    
    optimization: {
        minimize: isProduction,
        minimizer: [
            // JavaScript minification
            new TerserPlugin({
                terserOptions: {
                    compress: {
                        drop_console: isProduction, // Remove console.log in production
                        drop_debugger: isProduction,
                        pure_funcs: isProduction ? ['console.log', 'console.info'] : []
                    },
                    format: {
                        comments: false
                    }
                },
                extractComments: false
            }),
            
            // CSS minification
            new CssMinimizerPlugin({
                minimizerOptions: {
                    preset: [
                        'default',
                        {
                            discardComments: { removeAll: true }
                        }
                    ]
                }
            })
        ],
        
        // Code splitting
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                // Vendor code
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    priority: 10,
                    reuseExistingChunk: true
                },
                // Common code used across multiple entry points
                common: {
                    minChunks: 2,
                    priority: 5,
                    reuseExistingChunk: true,
                    name: 'common'
                }
            }
        },
        
        // Runtime chunk for better long-term caching
        runtimeChunk: {
            name: 'runtime'
        }
    },
    
    resolve: {
        extensions: ['.js', '.jsx', '.json'],
        alias: {
            '@': path.resolve(__dirname, 'src'),
            '@js': path.resolve(__dirname, 'src/js'),
            '@css': path.resolve(__dirname, 'src/css'),
            '@images': path.resolve(__dirname, 'src/images')
        }
    },
    
    performance: {
        hints: isProduction ? 'warning' : false,
        maxEntrypointSize: 512000,
        maxAssetSize: 512000
    },
    
    stats: {
        colors: true,
        hash: false,
        version: false,
        timings: true,
        assets: true,
        chunks: false,
        modules: false,
        children: false
    },
    
    devServer: {
        static: {
            directory: path.join(__dirname, 'dist')
        },
        compress: true,
        port: 3000,
        hot: true,
        open: false
    }
};
