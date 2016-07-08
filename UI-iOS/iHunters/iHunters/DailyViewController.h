//
//  DailyViewController.h
//  iHunters
//
//  Created by Marat on 31.05.16.
//  Copyright © 2016 iHuntersteam. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AllSites.h"

@interface DailyViewController : UIViewController <UIPickerViewDelegate, UIPickerViewDataSource>

@property (strong, nonatomic) NSDate *startDate;
@property (strong, nonatomic) NSDate *endDate;
@property (strong, nonatomic) NSString *searchWord;

- (IBAction)applyButton:(UIButton *)sender;

@end
