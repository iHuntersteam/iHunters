//
//  TotalStatsViewController.m
//  iHunters
//
//  Created by Nick Nevsky on 26.05.16.
//  Copyright © 2016 iHuntersteam. All rights reserved.
//

#import "TotalStatsViewController.h"
#import "AllSites.h"

@interface TotalStatsViewController () <UIPickerViewDelegate, UIPickerViewDataSource>

{
    NSArray *_sitesPickerData;
}

@property (weak, nonatomic) IBOutlet UIPickerView *sitesPicker;

@end

@implementation TotalStatsViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Initialize picker data
    AllSites *siteList = [[AllSites alloc] init];
    _sitesPickerData = siteList.sites;
    // Connect picker data
    self.sitesPicker.dataSource = self;
    self.sitesPicker.delegate = self;
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - sitesPicker delegate methods

// Number of columns
- (int)numberOfComponentsInPickerView:(UIPickerView *)sitesPicker {
    return 1;
}

// Number of rows
- (int)pickerView:(UIPickerView *)sitesPicker numberOfRowsInComponent:(NSInteger)component {
    return _sitesPickerData.count;
}

// Tha data to display in rows
- (NSString *)pickerView:(UIPickerView *)sitesPicker titleForRow:(NSInteger)row forComponent:(NSInteger)component {
    return _sitesPickerData[row];
}

// Capture the selection
- (void)pickerView:(UIPickerView *)sitesPicker didSelectRow:(NSInteger)row inComponent:(NSInteger)component {
    self.chosenSite = _sitesPickerData[row];
    // test
    NSLog(@"%@", self.chosenSite);
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
