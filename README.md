# Sacrina PHP SDK

## System Requirements

Sacrina PHP SDK runs PHP 5.6 or higher with cURL module enabled.

## Installation

You can install the package using composer

    composer require sacrina/sacrina
    
You can install and use it by cloning the repo.

    sudo git clone https://github.com/sacrina/sacrina-php.git
    
Then include the init.php file in your script.

    require_once('/path/to/sacrina-php/init.php');

## Configuration

Register for Sacrina account and get your key. The use:

    require_once 'sacrina-php/init.php';
    $sacrina = new sacrinarest();
    $sacrina->add_key('your API key');

## Usage

### Add dataset

You can add dataset as a list as:

    $list = ['data 1', 'data 2', 'data 3',..];
    $sacrina->add_dataset(list);

Or you can add data one by one as:

    $sacrina->add_data('Data 1');

### Upload dataset

Upload your dataset to Sacrina using:

    $sacrina->upload_dataset();

### Select an existing dataset

You can select an exisiting dataset using the id parameter:

    $sacrina->select_dataset($dataset_id);

### Create model

To create a new model using your added dataset, use the following:

    $sacrina->create_model();

### Select model

To select an existing model using its id, use the following:

    $sacrina->select_model($model_id);

### Train model

To start your model training, use the following:

    $sacrina->train_model();

### Check model status

To check the status of training, use the following:

    $sacrina->check_model_status();

### Create project

To create a new project, use the following:

    $sacrina->create_project($gen, $sector_min, $sector_max);

### Select project

To select an existing project using its id, use the following:

    $sacrina->select_project($project_id);

### Execute project

To execute the selected project, use the following:

    $sacrina->execute_project();

### Check project status

To check the project status, use the following:

    $sacrina->check_project_status();

### Download results

To download the results of the selected project, use the following:

    $sacrina->download_results();
