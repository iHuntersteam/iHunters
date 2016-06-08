//
//  DailyViewController.m
//  iHunters
//
//  Created by Marat on 31.05.16.
//  Copyright © 2016 iHuntersteam. All rights reserved.
//

#import "DailyViewController.h"

@interface DailyViewController ()

@property (strong, nonatomic) NSArray *pickerSitesArray;

@end

@implementation DailyViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    AllSites *allSites = [[AllSites alloc] init];
    
    self.pickerSitesArray = allSites.sites;
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - UIPickerViewDelegate

- (nullable NSString *)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
    
    return self.pickerSitesArray[row];
}

-(void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component {
    NSLog(@"%@", self.pickerSitesArray[row]);
}

#pragma mark - UIPickerViewDataSource

- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView {
    
    return 1;
}

- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component {
    
    return [self.pickerSitesArray count];
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

#pragma mark - Actions

- (IBAction)applyButton:(UIButton *)sender {
    NSLog(@"Apply button press");
}

@end
