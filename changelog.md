# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.0] - 2019-06-05
### Added
- Add an error message column to the batch table and populate it when a batch is marked stopped due to error

## [1.3.0] - 2019-04-22
### Added
- Added the QueueApiInterface to the DI definitions
- Made Corbomite Queue multiple-worker aware. Set up multiple workers to run the queue. Each batch keeps track of whether a worker is currently running it or not.
### Fixed
- Removed usage of deprecated methods on Corbomite DI
### Changed
- Changed typehints for DateTime to DateTimeInterface for flexibility
- Removed the need to define a constant for APP_BASE_PATH
- Internal change: updated all code to use the Doctrine coding standard
- Added PHPUnit 100% code coverage testing

## [1.2.0] - 2019-01-22
### Changed
- Updated services and methods to work with binary guids

## [1.1.1] - 2019-01-22
### Changed
- Updated tables to not have an incrementing integer as primary key

## [1.1.0] - 2019-01-21
### Added
- Added interfaces and implement them
- Added new Api Methods to fetch queue batches

## [1.0.0] - 2019-01-10
### New
- Initial Release
