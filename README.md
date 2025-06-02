# Contao Command Bundle

This Bundle adds in the Contao 5 backend a simple interface that allows administrators to run various Symfony and Contao console commands directly from the admin panel without needing command-line access.

> [!WARNING]
> This bundle is not compatible with Contao 4

## Features

- Run common Contao and Symfony commands with a simple click
- Interface integrated into the Contao backend
- Restricted to administrators for security
- Supports a wide range of commands:
  - System information (about)
  - Cache management (clear, warmup)
  - Contao-specific operations (symlinks, resize-images)
  - Debug commands for various components
  - Environment and configuration tools

## Requirements

- PHP 8.2 or higher
- Contao 5.x

## Installation

### Via Composer

```bash
composer require webexmachina/contao-command-bundle
```

### Manual Installation

1. Download the bundle from the [GitHub repository](https://github.com/Web-Ex-Machina/contao-command-bundle/)
2. Extract the ZIP file
3. Move the files to the `vendor/webexmachina/contao-command-bundle` directory in your Contao installation

### Managed Contao Installation (recommended)

It’s recommended to install the bundle directly via the Contao Manager.

## Usage

1. Log in to the Contao backend as an administrator
2. Click on "Commands" in the main menu 
3. Select the command you want to run from the dropdown list and click "Run" 
4. The command output will be directly displayed in the browser

## Available Commands

- **System Information**
  - About Contao: Display information about your Contao installation

- **Cache Management**
  - Empty the cache: Clear the application cache
  - Reload the cache: Warm up the application cache

- **Contao Operations**
  - Rebuild symbolic links: Recreate symbolic links for public resources
  - Resize images: Process images that haven't been resized yet

- **Debug Commands**
  - Multiple debug commands for inspecting various aspects of your application

- **Utility Commands**
  - Environment dump: Compile .env files to .env.local.php
  - Container linting: Check the validity of the configuration container
  - Router matching: Display available routes

## Contributing

Contributions are welcome! If you'd like to contribute, please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

For bug reports and feature requests, please use the [issue tracker](https://github.com/Web-Ex-Machina/contao-command-bundle/issues).

## License

This bundle is licensed under the Apache License 2.0. See the [LICENSE](LICENSE) file for more information.
