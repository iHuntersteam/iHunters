//
//  AllSites.m
//  iHunters
//
//  Created by Nick Nevsky on 31.05.16.
//  Copyright © 2016 iHuntersteam. All rights reserved.
//

#import "AllSites.h"

@implementation AllSites

// Массив-заглушка, пока нет класса для загрузки данных с сервера.
- (instancetype)init {
    self = [super init];
    if (self) {
        _sites = @[@"Lenta.ru", @"Newsru.com", @"Meduza.io"];
    }
    return self;
}

@end
