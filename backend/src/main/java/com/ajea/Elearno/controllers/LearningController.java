package com.ajea.Elearno.controllers;

import com.ajea.Elearno.models.AppUser;
import com.ajea.Elearno.models.LearningStyleUpdateRequest;
import com.ajea.Elearno.service.AppUserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@CrossOrigin(origins = "*", maxAge = 3600)
@RestController
@RequestMapping("/api/quiz")
public class LearningController {

    private static final Logger logger = LoggerFactory.getLogger(LearningController.class);
    private final AppUserService appUserService;

    @Autowired
    public LearningController(AppUserService appUserService) {
        this.appUserService = appUserService;
    }

    @PostMapping("/update/{userid}")
    public ResponseEntity<AppUser> updateUserLearningStyle(@PathVariable Long userid, @RequestBody LearningStyleUpdateRequest request) {
        logger.info("Received update for user {}: {}", userid, request);
        AppUser updatedUser = appUserService.updateUserLearningStyle(
                userid,
                request.getVisualCount(),
                request.getAuditoryCount(),
                request.getKinestheticCount()
        );
        return ResponseEntity.ok(updatedUser);
    }
}
