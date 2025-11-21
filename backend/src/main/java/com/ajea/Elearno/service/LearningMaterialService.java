package com.ajea.Elearno.service;

import com.ajea.Elearno.models.LearningMaterial;
import com.ajea.Elearno.models.dtos.ContentBlock;
import com.ajea.Elearno.repository.LearningMaterialRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.io.Resource;
import org.springframework.core.io.support.ResourcePatternResolver;
import org.springframework.stereotype.Service;
import org.springframework.util.FileCopyUtils;

import java.io.IOException;
import java.io.InputStreamReader;
import java.io.Reader;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

@Service
public class LearningMaterialService {

    private final LearningMaterialRepository learningMaterialRepository;
    private final ResourcePatternResolver resourceResolver;

    @Autowired
    public LearningMaterialService(LearningMaterialRepository learningMaterialRepository, ResourcePatternResolver resourceResolver) {
        this.learningMaterialRepository = learningMaterialRepository;
        this.resourceResolver = resourceResolver;
    }

    public List<LearningMaterial> getAllLearningMaterials() {
        return learningMaterialRepository.findAll();
    }

    public LearningMaterial getLearningMaterialById(Long id) {
        return learningMaterialRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Material not found"));
    }

    public List<ContentBlock> getContentForMaterial(Long id) throws IOException {
        List<ContentBlock> contentBlocks = new ArrayList<>();
        String pattern = "classpath:static/" + id + "/*";
        Resource[] resources = resourceResolver.getResources(pattern);

        Pattern filePattern = Pattern.compile("(\\d+)\\.(.+)");

        for (Resource resource : resources) {
            String filename = resource.getFilename();
            if (filename == null) continue;

            Matcher matcher = filePattern.matcher(filename);
            if (matcher.matches()) {
                String order = matcher.group(1);
                String extension = matcher.group(2);
                String path = "/" + id + "/" + filename;

                String type = switch (extension) {
                    case "txt" -> "text";
                    case "mp4", "mov", "webm" -> "video";
                    case "mp3", "wav", "ogg" -> "audio";
                    case "quiz" -> "quiz";
                    default -> "unknown";
                };

                ContentBlock block = new ContentBlock();
                block.setType(type);
                block.setPath(path);

                if ("quiz".equals(type)) {
                    try (Reader reader = new InputStreamReader(resource.getInputStream(), StandardCharsets.UTF_8)) {
                        String quizId = FileCopyUtils.copyToString(reader);
                        block.setQuizId(quizId.trim());
                    }
                }
                contentBlocks.add(block);
            }
        }

        contentBlocks.sort(Comparator.comparingInt(b -> {
            String[] parts = b.getPath().split("/");
            String filename = parts[parts.length - 1];
            return Integer.parseInt(filename.split("\\.")[0]);
        }));

        return contentBlocks;
    }
}
