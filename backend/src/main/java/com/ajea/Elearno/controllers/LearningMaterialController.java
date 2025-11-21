package com.ajea.Elearno.controllers;

import com.ajea.Elearno.models.LearningMaterial;
import com.ajea.Elearno.models.dtos.ContentBlock;
import com.ajea.Elearno.service.LearningMaterialService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import org.springframework.web.bind.annotation.PathVariable;

import java.io.IOException;
import java.util.List;

@CrossOrigin(origins = "*", maxAge = 3600)
@RestController
@RequestMapping("/api/materials")
public class LearningMaterialController {

    private final LearningMaterialService learningMaterialService;

    @Autowired
    public LearningMaterialController(LearningMaterialService learningMaterialService) {
        this.learningMaterialService = learningMaterialService;
    }

    @GetMapping
    public ResponseEntity<List<LearningMaterial>> getAllMaterials() {
        return ResponseEntity.ok(learningMaterialService.getAllLearningMaterials());
    }

    @GetMapping("/{id}")
    public ResponseEntity<LearningMaterial> getMaterialById(@PathVariable Long id) {
        return ResponseEntity.ok(learningMaterialService.getLearningMaterialById(id));
    }

    @GetMapping("/{id}/content")
    public ResponseEntity<List<ContentBlock>> getContentForMaterial(@PathVariable Long id) throws IOException {
        return ResponseEntity.ok(learningMaterialService.getContentForMaterial(id));
    }
}
